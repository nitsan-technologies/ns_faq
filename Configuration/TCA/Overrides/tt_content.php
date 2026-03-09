<?php

defined('TYPO3') || die('Access denied');

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$ctypeKey = ExtensionUtility::registerPlugin(
    'NsFaq',
    'Faq',
    'FAQs',
    'ns_faq-plugin-faq',
    'plugins'
);

ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,pages',
    $ctypeKey,
    'after:subheader',
);

// @extensionScannerIgnoreLine
ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:ns_faq/Configuration/FlexForm/NsFaq_flexForm.xml',
    $ctypeKey,
);
