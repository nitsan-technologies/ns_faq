<?php

namespace NITSAN\NsFaq\Controller;

use NITSAN\NsFaq\NsTemplate\ExtendedTemplateService;
use NITSAN\NsFaq\NsTemplate\TypoScriptTemplateConstantEditorModuleFunctionController;
use NITSAN\NsFaq\NsTemplate\TypoScriptTemplateModuleController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject as inject;

/***
 *
 * This file is part of the "[NITSAN] FAQ" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020
 *
 ***/

/**
 * FaqModuleController
 */
class FaqModuleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
    /**
     * faqRepository
     *
     * @var \NITSAN\NsFaq\Domain\Repository\FaqRepository
     * @inject
     */
    protected $faqRepository = null;

    protected $templateService;

    protected $constantObj;

    protected $constants;

    /**
    * @var TypoScriptTemplateModuleController
    */
    protected $pObj;

    protected $contentObject = null;

    protected $pid = null;

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
     * Initializes this object
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->contentObject = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
        $this->templateService = GeneralUtility::makeInstance(ExtendedTemplateService::class);
        $this->constantObj = GeneralUtility::makeInstance(TypoScriptTemplateConstantEditorModuleFunctionController::class);
    }

    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction()
    {
        parent::initializeAction();

        //GET and SET pid for the
        $this->pid = (GeneralUtility::_GP('id') ? GeneralUtility::_GP('id') : '0');
        $querySettings = $this->faqRepository->createQuery()->getQuerySettings();
        $querySettings->setStoragePageIds([$this->pid]);
        $this->faqRepository->setDefaultQuerySettings($querySettings);

        //GET CONSTANTs
        $this->constantObj->init($this->pObj);
        $this->constants = $this->constantObj->main();
    }

    /**
     * action dashboard
     *
     * @return void
     */
    public function dashboardAction()
    {
        //Fetch all FAQs
        $faqs = $this->faqRepository->findAll();

        $faqcount = count($faqs);

        $bootstrapVariable = 'data';

        $this->view->assignMultiple([
            'faqs' => $faqs,
            'action' => 'dashboard',
            'faqscount' => $faqcount,
            'pid' => $this->pid,
            'bootstrapVariable' => $bootstrapVariable,
            'menulist' => 'a,b'
        ]);


    }

    /**
     * action faqList
     *
     * @return void
     */
    public function faqListAction()
    {
        //Fetch page data
        $contentData = $this->contentObject->data;

        //Fetch all FAQs
        $faqs = $this->faqRepository->findAll();


        $bootstrapVariable = 'data';
        //Assign variables values
        $this->view->assignMultiple([
          'faqs' => $faqs,
          'data' => $contentData,
          'action' => 'faqList',
          'pid' => $this->pid,
          'bootstrapVariable' => $bootstrapVariable
        ]);
    }

    /**
     * action faqBasicSettings
     *
     * @return void
     */
    public function faqBasicSettingsAction()
    {
        $bootstrapVariable = 'data';

        $this->view->assignMultiple([
            'action' => 'faqBasicSettings',
            'constant' => $this->constants,
            'bootstrapVariable' => $bootstrapVariable
        ]);
    }

    /**
     * action saveConstant
     */
    public function saveConstantAction()
    {
        $this->constantObj->main();
        $_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action']; //get action name
        return false;
    }

}
