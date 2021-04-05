<?php
defined('TYPO3_MODE') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NITSAN.NsFaq',
    'Faq',
    [
        'Faq' => 'list',
    ],
    // non-cacheable actions
    [
        'Faq' => '',
    ]
);

// wizards
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('<INCLUDE_TYPOSCRIPT: source="FILE:EXT:ns_faq/Configuration/TSconfig/ContentElementWizard.txt">');

$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);

$iconRegistry->registerIcon(
    'ns_faq-plugin-faq',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:ns_faq/Resources/Public/Icons/ns_faq.svg']
);
$iconRegistry->registerIcon(
    'module-nsfaq',
    \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
    ['source' => 'EXT:ns_faq/Resources/Public/Icons/module-nitsan.svg']
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['cms/layout/class.tx_cms_layout.php']['tt_content_drawItem']['ns_faq'] = 'NITSAN\\NsFaq\\Hooks\\PageLayoutView';
