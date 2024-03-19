<?php
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
defined('TYPO3_MODE') || die('Access denied.');

$_EXTKEY = 'ns_faq';

ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'NS FAQs');
