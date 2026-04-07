<?php

declare(strict_types=1);

namespace NITSAN\NsFaq\Updates;

use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Install\Attribute\UpgradeWizard;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\CMS\Install\Updates\UpgradeWizardInterface;

#[UpgradeWizard('txNsFaqPluginMigration')]
class PluginMigration implements UpgradeWizardInterface
{
    private const LEGACY_PLUGIN_LIST_TYPE = 'nsfaq_faq';
    private const LEGACY_PLUGIN_CTYPE = 'list';
    private const NEW_CTYPE = 'nsfaq_faq';

    public function getTitle(): string
    {
        return 'EXT:ns_faq: Migrate legacy plugin to CType';
    }

    public function getDescription(): string
    {
        $count = count($this->getMigrationRecords());
        return 'Migrates legacy FAQ plugin records from CType=list + list_type=nsfaq_faq to CType=nsfaq_faq. '
            . 'FlexForm and all other fields stay unchanged. '
            . 'Records to migrate: ' . $count;
    }

    public function getPrerequisites(): array
    {
        return [
            DatabaseUpdatedPrerequisite::class,
        ];
    }

    public function updateNecessary(): bool
    {
        return $this->checkIfWizardIsRequired();
    }

    public function executeUpdate(): bool
    {
        return $this->performMigration();
    }

    public function checkIfWizardIsRequired(): bool
    {
        return count($this->getMigrationRecords()) > 0;
    }

    public function performMigration(): bool
    {
        $records = $this->getMigrationRecords();

        foreach ($records as $record) {
            $this->updateContentElement((int)$record['uid']);
        }

        return true;
    }

    protected function getMigrationRecords(): array
    {
        if (!$this->hasListTypeColumn()) {
            return [];
        } else {
            $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
            $queryBuilder = $connectionPool->getQueryBuilderForTable('tt_content');
            $queryBuilder->getRestrictions()->removeAll()->add(GeneralUtility::makeInstance(DeletedRestriction::class));

            return $queryBuilder
                ->select('uid', 'pid', 'CType', 'list_type')
                ->from('tt_content')
                ->where(
                    $queryBuilder->expr()->eq(
                        'CType',
                        $queryBuilder->createNamedParameter(self::LEGACY_PLUGIN_CTYPE)
                    ),
                    $queryBuilder->expr()->eq(
                        'list_type',
                        $queryBuilder->createNamedParameter(self::LEGACY_PLUGIN_LIST_TYPE)
                    )
                )
                ->executeQuery()
                ->fetchAllAssociative();
        }
    }

    protected function hasListTypeColumn(): bool
    {
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $columns = $connection->createSchemaManager()->listTableColumns('tt_content');
        return isset($columns['list_type']);
    }

    /**
     * Updates CType and clears list_type of the given content element UID.
     * Does not touch pi_flexform or any other fields.
     */
    protected function updateContentElement(int $uid): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $queryBuilder->update('tt_content')
            ->set('CType', self::NEW_CTYPE)
            ->set('list_type', '')
            ->where(
                $queryBuilder->expr()->eq(
                    'uid',
                    $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)
                )
            )
            ->executeStatement();
    }
}
