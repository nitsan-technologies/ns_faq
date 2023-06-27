<?php
defined('TYPO3') || die('Access denied.');

$_EXTKEY = 'ns_faq';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'NS FAQs');
