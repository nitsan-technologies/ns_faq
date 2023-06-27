<?php
namespace NITSAN\NsFaq\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Template\ModuleTemplate;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use NITSAN\NsFaq\NsTemplate\TypoScriptTemplateModuleController;

/***
 *
 * This file is part of the "[NITSAN] FAQ" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023
 *
 ***/

/**
 * FaqModuleController
 */
class FaqModuleController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory
    ) {
    }
    
    /**
     * faqRepository
     *@var mixed
     */
    protected $sidebarData;
    
    /**
     * faqRepository
     *@var mixed
     */
    protected $dashboardSupportData;

    /**
     * faqRepository
     *@var mixed
     */
    protected $contentObject = null;

    /**
     * faqRepository
     *@var mixed
     */    
    protected $pid = null;
    
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
     * Initializes this object
     *
     * @return void
     */
    public function initializeObject()
    {
        $this->contentObject = GeneralUtility::makeInstance('TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer');
    }

    /**
     * action dashboard
     */
    public function dashboardAction(): ResponseInterface
    {
        $view = $this->initializeModuleTemplate($this->request);
        //Fetch all FAQs
        $faqs = $this->faqRepository->findAll();

        $faqcount = count($faqs);
        //Assign variables values
        $this->view->assign('menulist', 'a,b');
        $bootstrapVariable = 'data-bs';
        $assign = [
          'faqs' => $faqs,
          'action' => 'dashboard',
          'faqscount' => $faqcount,
          'pid' => $this->pid,
          'rightSide' => $this->sidebarData,
          'dashboardSupport' => $this->dashboardSupportData,
          'bootstrapVariable' => $bootstrapVariable
        ];
        $view->assignMultiple($assign);
        return $view->renderResponse();
    }

    /**
     * action faqList
     *
         */
    public function faqListAction(): ResponseInterface
    {
        $view = $this->initializeModuleTemplate($this->request);
        //Fetch page data
        $contentData = $this->contentObject->data;

        //Fetch all FAQs
        $faqs = $this->faqRepository->findAll();

        //Fetch Plugin Settings
        $settings = $this->settings;
        $bootstrapVariable = 'data-bs';
        //Assign variables values
        $assign = [
          'faqs' => $faqs,
          'data' => $contentData,
          'action' => 'faqList',
          'pid' => $this->pid,
          'bootstrapVariable' => $bootstrapVariable
        ];
        $view->assignMultiple($assign);
        return $view->renderResponse();
    }

    /**
     * Generates the action menu
     */
    protected function initializeModuleTemplate(
        ServerRequestInterface $request
    ): ModuleTemplate {
        return $this->moduleTemplateFactory->create($request);
    }
}
