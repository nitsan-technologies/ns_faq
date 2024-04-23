<?php


use NITSAN\NsFaq\Controller\NsConstantEditorController;
use NITSAN\NsFaq\Controller\FaqModuleController;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$module = [
    'nitsan_nsfaqnitsan_configuration' => [
        'parent' => 'nitsan_module',
        'position' => ['before' => 'top'],
        'access' => 'user',
        'path' => '/module/nitsan/NsFaqConfiguration',
        'iconIdentifier'   => 'nsfaq_icon',
        'labels' => 'LLL:EXT:ns_faq/Resources/Private/Language/locallang_faqconstants.xlf',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'extensionName' => 'NsFaq',
        'routes' => [
            '_default' => [
                'target' => NsConstantEditorController::class . '::handleRequest',
            ],
        ],
        'moduleData' => [
            'selectedTemplatePerPage' => [],
            'selectedCategory' => '',
        ],
    ],
    'nitsan_nsfaqnitsan_constants' => [
        'parent' => 'nitsan_module',
        'position' => ['before' => 'top'],
        'access' => 'user',
        'path' => '/module/nitsan/NsFaqNitsan',
        'iconIdentifier' => 'nsfaq_icon',
        'labels' => 'LLL:EXT:ns_faq/Resources/Private/Language/locallang_faq.xlf',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'extensionName' => 'NsFaq',
        'controllerActions' => [
            FaqModuleController::class => [
                'dashboard',
                'faqList'
            ],
        ],
    ],
];

if (!ExtensionManagementUtility::isLoaded('ns_basetheme')) {
    $module['nitsan_module'] = [
        'labels' => 'LLL:EXT:ns_faq/Resources/Private/Language/BackendModule.xlf',
        'iconIdentifier' => 'module-nsfaq',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'position' => ['after' => 'web'],
    ];
}

return $module;
