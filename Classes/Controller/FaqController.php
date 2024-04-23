<?php

namespace NITSAN\NsFaq\Controller;

use TYPO3\CMS\Extbase\Annotation\Inject as inject;

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
class FaqController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    protected $pageUid;
    /**
     * faqRepository
     *
     * @var \NITSAN\NsFaq\Domain\Repository\FaqRepository
     * @inject
     */
    protected $faqRepository = null;

    /**
     * Inject a faqRepository
     *
     * @param \NITSAN\NsFaq\Domain\Repository\FaqRepository
     */
    public function injectFaqRepository(\NITSAN\NsFaq\Domain\Repository\FaqRepository $faqRepository)
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
        $this->pageUid = $GLOBALS['TSFE']->id;
    }
    /**
     * action list
     *
     * @return void
     */
    public function listAction()
    {

        //Fetch page data
        $contentData = $this->configurationManager->getContentObject();
        $data = $contentData->data;

        //Fetch all FAQs
        $faqs = $this->faqRepository->findAll();

        //Assign variables values
        $this->view->assignMultiple([
            'faqs' => $faqs,
            'data' => $data,
        ]);
    }
}
