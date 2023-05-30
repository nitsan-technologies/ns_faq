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
 *  (c) 2020
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
     *
     */
    protected $faqRepository = null;

    protected $constantObj;

    protected $sidebarData;

    protected $dashboardSupportData;

    protected $generalFooterData;

    protected $premiumExtensionData;

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
    }

    /**
     * Initialize Action
     *
     * @return void
     */
    public function initializeAction()
    {
        parent::initializeAction();
        //Links for the All Dashboard VIEW from API...
        $sidebarUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_faq&blockName=DashboardRightSidebar';
        $dashboardSupportUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_faq&blockName=DashboardSupport';
        $generalFooterUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_faq&blockName=GeneralFooter';
        $premiumExtensionUrl = 'https://composer.t3terminal.com/API/ExtBackendModuleAPI.php?extKey=ns_faq&blockName=PremiumExtension';

        $this->faqRepository->deleteOldApiData();
        $checkApiData = $this->faqRepository->checkApiData();
        if (!$checkApiData) {
            $this->sidebarData = $this->faqRepository->curlInitCall($sidebarUrl);
            $this->dashboardSupportData = $this->faqRepository->curlInitCall($dashboardSupportUrl);
            $this->generalFooterData = $this->faqRepository->curlInitCall($generalFooterUrl);
            $this->premiumExtensionData = $this->faqRepository->curlInitCall($premiumExtensionUrl);

            $data = [
                'right_sidebar_html' => $this->sidebarData,
                'support_html'=> $this->dashboardSupportData,
                'footer_html' => $this->generalFooterData,
                'premuim_extension_html' => $this->premiumExtensionData,
                'extension_key' => 'ns_faq',
                'last_update' => date('Y-m-d')
            ];
            $this->faqRepository->insertNewData($data);
        } else {
            $this->sidebarData = $checkApiData['right_sidebar_html'];
            $this->dashboardSupportData = $checkApiData['support_html'];
            $this->premiumExtensionData = $checkApiData['premuim_extension_html'];
        }

        //GET and SET pid for the
        $pageId = $this->request->getQueryParams();
        $this->pid = ($pageId['id'] ? $pageId['id'] : '0');
        $querySettings = $this->faqRepository->createQuery()->getQuerySettings();
        $querySettings->setStoragePageIds([$this->pid]);
        $this->faqRepository->setDefaultQuerySettings($querySettings);
    }

    /**
     * action dashboard
     *
     * @return void
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
     * @return void
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
