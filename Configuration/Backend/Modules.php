<?php

use FaqModuleController;

return [
    'nitsan_module' => [
        'labels' => 'LLL:EXT:ns_faq/Resources/Private/Language/BackendModule.xlf',
        'icon' => 'EXT:ns_faq/Resources/Public/Icons/module-nsfaq.svg',
        'iconIdentifier' => 'module-nsfaq',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'position' => ['after' => 'web'],
    ],
    'nitsan_nsfaqnitsan_configuration' => [
        'parent' => 'nitsan_module',
        'position' => ['before' => 'top'],
        'access' => 'admin,user,group',
        'path' => '/module/nitsan/NsFaqConfiguration',
        'iconIdentifier'   => 'ns_faq-plugin-faq',
        'labels' => 'LLL:EXT:ns_faq/Resources/Private/Language/locallang_faqconstants.xlf',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'extensionName' => 'NsFaq',
        'routes' => [
            '_default' => [
                'target' => \NITSAN\NsFaq\Controller\NsConstantEditorController::class . '::handleRequest',
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
        'access' => 'user,group',
        'path' => '/module/nitsan/NsFaqNitsan',
        'iconIdentifier' => 'ns_faq-plugin-faq',
        'labels' => 'LLL:EXT:ns_faq/Resources/Private/Language/locallang_faq.xlf',
        'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
        'extensionName' => 'NsFaq',
        'controllerActions' => [
            \NITSAN\NsFaq\Controller\FaqModuleController::class => [
                'dashboard',
                'faqList',
                'faqCategories',
                'faqBasicSettings', 
            ],
        ],
    ],
];
