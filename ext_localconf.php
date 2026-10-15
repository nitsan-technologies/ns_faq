<?php

defined('TYPO3') || die('Access denied.');

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use NITSAN\NsFaq\Controller\FaqController;

ExtensionUtility::configurePlugin(
    'NsFaq',
    'Faq',
    [
        FaqController::class => 'list',
    ],
    // non-cacheable actions
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);

$GLOBALS['TYPO3_CONF_VARS']['SYS']['formEngine']['nodeResolver'][1792047381] = [
    'nodeName' => 'flexFormNoTabsContainer',
    'priority' => 45,
    'class' => \NITSAN\NsFaq\Form\Resolver\PremiumStripFlexFormNoTabsContainerResolver::class,
];