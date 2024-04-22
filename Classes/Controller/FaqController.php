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
 * This file is part of the "NS FAQs" Extension for TYPO3 CMS.
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
     *@var mixed
     */
    protected $faqRepository = null;

    /**
     * Inject a faqRepository
     *
     * @param FaqRepository $faqRepository
     */
    public function injectFaqRepository(FaqRepository $faqRepository): void
    {
        $this->faqRepository = $faqRepository;
    }

    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction()
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

        $contentObjectRenderer = GeneralUtility::makeInstance(ContentObjectRenderer::class);
        $data = $contentObjectRenderer->data;

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
            'faqs' => $faqs,
            'data' => $data,
        ]);

        return $this->htmlResponse();
    }
}
