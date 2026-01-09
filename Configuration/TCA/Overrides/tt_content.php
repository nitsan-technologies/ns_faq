<?php

defined('TYPO3') || die('Access denied');

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\VersionNumberUtility;

$versionNumber =  VersionNumberUtility::convertVersionStringToArray(VersionNumberUtility::getCurrentTypo3Version());

if ($versionNumber['version_main'] <= '12') {
    ExtensionUtility::registerPlugin(
        'NsFaq',
        'Faq',
        'FAQs',
        'ns_faq-plugin-faq',
        'plugins'
    );

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['nsfaq_faq'] = 'recursive,select_key';

    $GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['nsfaq_faq'] = 'pi_flexform';
    // @extensionScannerIgnoreLine
    ExtensionManagementUtility::addPiFlexFormValue('nsfaq_faq', 'FILE:EXT:ns_faq/Configuration/FlexForm/NsFaq_flexForm.xml');
} else {
    $ctypeKey = ExtensionUtility::registerPlugin(
        'NsFaq',
        'Faq',
        'LLL:EXT:ns_faq/Resources/Private/Language/locallang_db.xlf:tx_ns_faq_faq.name',
        'ns_faq-plugin-faq',
        'plugins',
        'LLL:EXT:ns_faq/Resources/Private/Language/locallang_db.xlf:tx_ns_faq_faq.description',
        'FILE:EXT:ns_faq/Configuration/FlexForm/NsFaq_flexForm.xml'
    );

    ExtensionManagementUtility::addToAllTCAtypes(
        'tt_content',
        '--div--;Configuration,pi_flexform,',
        $ctypeKey,
        'after:subheader',
    );

    // @extensionScannerIgnoreLine
    ExtensionManagementUtility::addPiFlexFormValue(
        '*',
        'FILE:EXT:ns_faq/Configuration/FlexForm/NsFaq_flexForm.xml',
        $ctypeKey,
    );
}
