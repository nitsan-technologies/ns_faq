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