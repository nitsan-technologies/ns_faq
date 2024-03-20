<?php

use TYPO3\CMS\Core\Utility\VersionNumberUtility;
use NITSAN\NsFaq\Controller\FaqModuleController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3_MODE') || die('Access denied.');
$_EXTKEY = 'ns_faq';
if (TYPO3_MODE === 'BE') {
    $isVersion9Up = VersionNumberUtility::convertVersionNumberToInteger(TYPO3_version) >= 9000000;
    if (!array_key_exists('nitsan', $GLOBALS['TBE_MODULES']) || $GLOBALS['TBE_MODULES']['nitsan'] == '') {
        if ((version_compare(TYPO3_branch, '8.0', '>=')) &&
            (!isset($GLOBALS['TBE_MODULES']['nitsan']))) {

            $temp_TBE_MODULES = [];
            foreach ($GLOBALS['TBE_MODULES'] as $key => $val) {
                if ($key == 'web') {
                    $temp_TBE_MODULES[$key] = $val;
                    $temp_TBE_MODULES['nitsan'] = '';
                } else {
                    $temp_TBE_MODULES[$key] = $val;
                }
            }

            $GLOBALS['TBE_MODULES'] = $temp_TBE_MODULES;
            $GLOBALS['TBE_MODULES']['_configuration']['nitsan'] = [
                'iconIdentifier' => 'module-nsfaq',
                'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/BackendModule.xlf',
                'name' => 'nitsan'
            ];
        }
    }

    if (version_compare(TYPO3_branch, '10.0', '>=')) {
        $faqController = FaqModuleController::class;
    } else {
        $faqController = 'FaqModule';
    }
    ExtensionUtility::registerModule(
        'NITSAN.NsFaq',
        'nitsan', // Make module a submodule of 'nitsan'
        'faqbackend', // Submodule key
        '', // Position
        [
            $faqController => 'dashboard, faqList, faqBasicSettings, premiumExtension, show, new, create, edit, update,
            delete, saveConstant',
        ],
        [
            'access' => 'user,group',
            'icon' => 'EXT:ns_faq/Resources/Public/Icons/ns_faq.svg',
            'labels' => 'LLL:EXT:ns_faq/Resources/Private/Language/locallang_faq.xlf',
            'navigationComponentId' => ($isVersion9Up ? 'TYPO3/CMS/Backend/PageTree/PageTreeElement' :
                'typo3-pagetree'),
            'inheritNavigationComponentFromMainModule' => false
        ]
    );
}
//plugin
ExtensionManagementUtility::addLLrefForTCAdescr(
    'tx_nsfaq_domain_model_faq',
    'EXT:ns_faq/Resources/Private/Language/locallang_csh_tx_nsfaq_domain_model_faq.xlf'
);
ExtensionManagementUtility::allowTableOnStandardPages('tx_nsfaq_domain_model_faq');
