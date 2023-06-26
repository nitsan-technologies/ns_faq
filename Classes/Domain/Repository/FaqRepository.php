<?php
namespace NITSAN\NsFaq\Domain\Repository;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
class FaqRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    protected $defaultOrderings = ['sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];

    /**
     * Returns the query
     *
     * @return string 
     */
    public function checkApiData()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nsfaq_domain_model_apidata');
        $queryBuilder
            ->select('*')
            ->from('tx_nsfaq_domain_model_apidata');
        $query = $queryBuilder->executeQuery();
        return $query->fetch();
    }

    /**
     * Returns the query
     *
     * @return mixed 
     * @param mixed $data
     */
    public function insertNewData($data)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nsfaq_domain_model_apidata');
        $row = $queryBuilder
            ->insert('tx_nsfaq_domain_model_apidata')
            ->values($data);

        $query = $queryBuilder->executeQuery();
        return $query;
    }

    /**
     * Returns the query
     *
     * @return mixed 
     * @param mixed $url
     */
    public function curlInitCall($url)
    {
        $curlSession = curl_init();
        curl_setopt($curlSession, CURLOPT_URL, $url);
        curl_setopt($curlSession, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER, true);
        $data = curl_exec($curlSession);
        curl_close($curlSession);

        return $data;
    }

    /**
     * Returns the query
     *
     * @return mixed 
     */
    public function deleteOldApiData()
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_nsfaq_domain_model_apidata');
        $queryBuilder
            ->delete('tx_nsfaq_domain_model_apidata')
            ->where(
                $queryBuilder->expr()->comparison('last_update', '<', 'DATE_SUB(NOW() , INTERVAL 1 DAY)')
            )
            ->executeQuery();
    }

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
