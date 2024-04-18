<?php

namespace NITSAN\NsFaq\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;

/***
 *
 * This file is part of the "NS FAQs" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019
 *
 ***/
/**
 * The repository for Faqs
 */
class FaqRepository extends Repository
{
    protected $defaultOrderings = ['sorting' => QueryInterface::ORDER_ASCENDING];


    /**
      * Find Constants via sys_template Database Table
      *
      * @param int $pid
      * @return mixed
      */
    public function fetchConstants($pid)
    {   //
        // Query Builder for Table: sys_template
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('sys_template');

        // Get Constants of Row, where RM Registration is included
        $query = $queryBuilder
            ->select('constants')
            ->from('sys_template')
            ->where(
                $queryBuilder->expr()->like(
                    'pid',
                    $queryBuilder->createNamedParameter($pid)
                )
            );

        // Execute Query and Return the Query-Fetch
        $query = $queryBuilder->executeQuery();
        return $query->fetch();
    }
}
