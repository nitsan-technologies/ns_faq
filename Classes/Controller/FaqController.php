<?php

namespace NITSAN\NsFaq\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use NITSAN\NsFaq\Domain\Repository\FaqRepository;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/***
 *
 * This file is part of the "FAQs" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2019
 *
 ***/
/**
 * FaqController
 */
class FaqController extends ActionController
{
    /**
     * faqRepository
     *
     * @var FaqRepository
     *
     */
    protected $faqRepository = null;

    /**
     * @param FaqRepository $faqRepository
     */
    public function __construct(
       
        FaqRepository $faqRepository,
   
        ) {
        $this->faqRepository = $faqRepository;
        
    }


    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction() :void
    {
        parent::initializeAction();
    }
    /**
     * action list
     *
     */
    public function listAction(): ResponseInterface
    {
        //Fetch Plugin Settings
        $settings = $this->settings;

       
       
       
        //Fetch all FAQs
        $faqs = $this->faqRepository->findAll();

        //Add Custom CSS
        $pageRender = GeneralUtility::makeInstance(PageRenderer::class);
        $settings['usercss'] = isset($settings['usercss']) ? $settings['usercss'] : '';
        if ($settings['usercss']) {
            $pageRender->addCssFile($settings['usercss'], 'stylesheet', '', '', true);
        } elseif ($settings['basicSettings']['general']['customCSS']) {
            $pageRender->addCssInlineBlock('ns-faq-custom-css', $settings['basicSettings']['general']['customCSS']);
        }

        //Assign variables values
        $this->view->assignMultiple([
            'faqs' => $faqs
            
        ]);

        return $this->htmlResponse();
    }
}
