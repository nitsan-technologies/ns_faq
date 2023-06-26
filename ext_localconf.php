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

$icons = [
    'ns_faq-plugin-faq' => 'ns_faq.svg',
    'module-nsfaq' => 'module-nitsan.svg',
    'nsfaq_icon' => 'ns_faq.svg'
];

foreach ($icons as $identifier => $path) {
    $iconRegistry->registerIcon(
        $identifier,
        \TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
        ['source' => 'EXT:ns_faq/Resources/Public/Icons/' . $path]
    );
}