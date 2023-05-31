<?php
defined('TYPO3') || die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'NsFaq',
    'Faq',
    [
        \NITSAN\NsFaq\Controller\FaqController::class => 'list',
    ],
    // non-cacheable actions
    []
);

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

