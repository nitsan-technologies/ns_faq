<?php
use NITSAN\NsFaq\Controller\FaqController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

defined('TYPO3_MODE') || die('Access denied.');
if (version_compare(TYPO3_branch, '10.0', '>=')) {
    $faqController = FaqController::class;
} else {
    $faqController = 'Faq';
}
ExtensionUtility::configurePlugin(
    'NITSAN.NsFaq',
    'Faq',
    [
        $faqController => 'list',
    ],
    // non-cacheable actions
    []
);

// wizards
ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ns_faq/Configuration/TSconfig/ContentElementWizard.tsconfig">');

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);

$iconRegistry->registerIcon(
    'ns_faq-plugin-faq',SvgIconProvider::class,
    ['source' => 'EXT:ns_faq/Resources/Public/Icons/ns_faq.svg']
);
$iconRegistry->registerIcon(
    'module-nsfaq',SvgIconProvider::class,
    ['source' => 'EXT:ns_faq/Resources/Public/Icons/module-nitsan.svg']
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['ns_faq'] = 'NITSAN\\NsFaq\\Hooks\\PageLayoutView';
