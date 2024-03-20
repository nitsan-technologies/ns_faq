<?php
defined('TYPO3') || die('Access denied.');
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility ;
$_EXTKEY = 'ns_faq';

//plugin
ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_nsfaq_domain_model_faq',
    'EXT:ns_faq/Resources/Private/Language/locallang_csh_tx_nsfaq_domain_model_faq.xlf'
);
ExtensionManagementUtility::allowTableOnStandardPages('tx_nsfaq_domain_model_faq');
