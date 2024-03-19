<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
defined('TYPO3') || die('Access denied.');

ExtensionManagementUtility::addStaticFile('ns_faq', 'Configuration/TypoScript', 'NS FAQs');
