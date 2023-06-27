<?php
namespace NITSAN\NsFaq\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

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
class FaqController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * faqRepository
     *@var mixed
     */
    protected $faqRepository = null;

    /**
     * Inject a faqRepository
     *
     * @param \NITSAN\NsFaq\Domain\Repository\FaqRepository $faqRepository
     */
    public function injectFaqRepository(\NITSAN\NsFaq\Domain\Repository\FaqRepository $faqRepository): void
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

        //Fetch page data
        $contentData = $this->configurationManager->getContentObject();
        $data = $contentData->data;

        //Fetch all FAQs
        $faqs = $this->faqRepository->findAll();

        //Add Custom CSS
        $pageRender = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Page\PageRenderer::class);
        $settings['usercss'] = isset($settings['usercss']) ? $settings['usercss'] : '';
        if ($settings['usercss']) {
            $pageRender->addCssFile($settings['usercss'], 'stylesheet', '', '', true);
        } elseif ($settings['basicSettings']['general']['customCSS']) {
            $pageRender->addCssInlineBlock('ns-faq-custom-css', $settings['basicSettings']['general']['customCSS']);
        }

        //Assign variables values
        $assign = [
            'faqs' => $faqs,
            'data' => $data,
        ];
        $this->view->assignMultiple($assign);

        return $this->htmlResponse();
    }
}
