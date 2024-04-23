<?php

defined('TYPO3_MODE') || die('Access denied.');

$_EXTKEY = 'ns_faq';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript', 'FAQs');
