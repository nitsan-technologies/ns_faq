<?php

defined('TYPO3') || die('Access denied.');

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use NITSAN\NsFaq\Controller\FaqController;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

$versionNumber =  VersionNumberUtility::convertVersionStringToArray(VersionNumberUtility::getCurrentTypo3Version());

if ($versionNumber['version_main'] <= '12') {
    // @extensionScannerIgnoreLine
    ExtensionUtility::configurePlugin(
        'NsFaq',
        'Faq',
        [
            FaqController::class => 'list',
        ],
        // non-cacheable actions
        []
    );
} else {
    ExtensionUtility::configurePlugin(
        'NsFaq',
        'Faq',
        [
            FaqController::class => 'list',
        ],
        // non-cacheable actions
        [],
        ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
    );
}
