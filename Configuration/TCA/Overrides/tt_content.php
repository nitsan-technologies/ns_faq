<?php


use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') || die('Access denied');
ExtensionUtility::registerPlugin(
    'NsFaq',
    'Faq',
    'FAQs',
    '',
    'plugins'
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['nsfaq_faq'] = 'recursive,select_key';

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['nsfaq_faq'] = 'pi_flexform';
ExtensionManagementUtility::addPiFlexFormValue('nsfaq_faq', 'FILE:EXT:ns_faq/Configuration/FlexForm/NsFaq_flexForm.xml');
