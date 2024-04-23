<?php

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use NITSAN\NsFaq\Controller\FaqController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Imaging\IconRegistry;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

defined('TYPO3') || die('Access denied.');

ExtensionUtility::configurePlugin(
    'NsFaq',
    'Faq',
    [
        FaqController::class => 'list',
    ],
    // non-cacheable actions
    []
);

$iconRegistry = GeneralUtility::makeInstance(IconRegistry::class);

$icons = [
    'ns_faq-plugin-faq' => 'ns_faq.svg',
    'module-nsfaq' => 'module-nitsan.svg',
    'nsfaq_icon' => 'ns_faq.svg'
];

foreach ($icons as $identifier => $path) {
    $iconRegistry->registerIcon(
        $identifier,
        SvgIconProvider::class,
        ['source' => 'EXT:ns_faq/Resources/Public/Icons/' . $path]
    );
}
