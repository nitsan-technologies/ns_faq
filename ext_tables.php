<?php
defined('TYPO3') || die('Access denied.');
$_EXTKEY = 'ns_faq';

//plugin
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_nsfaq_domain_model_faq', 'EXT:ns_faq/Resources/Private/Language/locallang_csh_tx_nsfaq_domain_model_faq.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_nsfaq_domain_model_faq');
