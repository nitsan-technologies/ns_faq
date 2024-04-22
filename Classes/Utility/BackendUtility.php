<?php

namespace NITSAN\NsFaq\Utility;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Http\RequestHandler;


/**
 * Backend utility functions
 *
 */
class BackendUtility extends AbstractUtility
{

    /**
     * Create an URI to edit any record
     *
     * @param string $tableName
     * @param int $identifier
     * @param bool $addReturnUrl
     * @return string
     */
    public static function createEditUri($tableName, $identifier, $addReturnUrl = true)
    {
        $uriParameters = [
            'edit' => [
                $tableName => [
                    $identifier => 'edit',
                ],
            ],
        ];
        if ($addReturnUrl) {
            $uriParameters['returnUrl'] = self::getReturnUrl();
        }
        return self::getModuleUrl('record_edit', $uriParameters);
    }

    /**
     * Create an URI to new record
     *
     * @param string $tableName
     * @param int $identifier
     * @param bool $addReturnUrl
     * @return string
     */
    public static function createNewUri($tableName, $identifier, $addReturnUrl = true)
    {
        if ($identifier <= 0) {
            $identifier = self::getPidFromBackendPage(self::getReturnUrl());
        }

        $uriParameters = [
            'edit' => [
                $tableName => [
                    $identifier => 'new',
                ],
            ],
        ];
        if ($addReturnUrl) {
            $uriParameters['returnUrl'] = self::getReturnUrl();
        }
        return self::getModuleUrl('record_edit', $uriParameters);
    }

    /**
     * Get return URL from current request
     *
     * @return string
     */
    protected static function getReturnUrl()
    {
        return GeneralUtility::getIndpEnv('REQUEST_URI');
    }

    /**
     * Returns the URL to a given module mainly used for visibility settings or deleting a record via AJAX
     * @param string $moduleName Name of the module
     * @param array $urlParameters URL parameters that should be added as key value pairs
     * @return string Calculated URL
     */
    public static function getModuleUrl($moduleName, $urlParameters = [])
    {
        $uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
        return $uriBuilder->buildUriFromRoute($moduleName, $urlParameters);
    }

    /**
     * @param string $returnUrl
     * @return int
     */
    public static function getPidFromBackendPage($returnUrl = '')
    {
        $queryParts = [];

        if (empty($returnUrl)) {
            $request = GeneralUtility::makeInstance(RequestHandler::class)->getRequest();
            $queryParams = $request->getQueryParams();
            $returnUrl = $queryParams['returnUrl'] ?? '';
        }

        if (!empty($returnUrl)) {
            $urlParts = parse_url($returnUrl);
            parse_str($urlParts['query'] ?? '', $queryParts);
        }

        return (int) ($queryParts['id'] ?? 0);
    }

}
