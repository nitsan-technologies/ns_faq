<?php
namespace NITSAN\NsFaq\Controller;

use NITSAN\NsFaq\NsTemplate\ExtendedTemplateService;
use NITSAN\NsFaq\NsTemplate\TypoScriptTemplateConstantEditorModuleFunctionController;
use NITSAN\NsFaq\NsTemplate\TypoScriptTemplateModuleController;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Annotation\Inject as inject;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility as transalte;

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
        //Assign variables values
        $this->view->assign('menulist', 'a,b');
        $assign = [
          'faqs' => $faqs,
          'action' => 'dashboard',
          'faqscount' => $faqcount,
          'pid' => $this->pid,
          'rightSide' => $this->sidebarData,
          'dashboardSupport' => $this->dashboardSupportData
        ];
        $this->view->assignMultiple($assign);
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

        //Fetch Plugin Settings
        $settings = $this->settings;

        //Assign variables values
        $assign = [
          'faqs' => $faqs,
          'data' => $contentData,
          'action' => 'faqList',
          'pid' => $this->pid
        ];
        $this->view->assignMultiple($assign);
    }

    /**
     * action faqBasicSettings
     *
     * @return void
     */
    public function faqBasicSettingsAction()
    {
        $assign = [
            'action' => 'faqBasicSettings',
            'constant' => $this->constants
        ];
        $this->view->assignMultiple($assign);
    }

    /**
     * action saveConstant
     */
    public function saveConstantAction()
    {
        $this->constantObj->main();
        $returnAction = $_REQUEST['tx_nsfaq_nitsan_nsfaqfaqbackend']['__referrer']['@action']; //get action name
        return false;
    }

    /**
     * action premiumExtension
     *
     * @return void
     */
    public function premiumExtensionAction()
    {
        $assign = [
            'action' => 'premiumExtension',
            'premiumExdata' => $this->premiumExtensionData
        ];
        $this->view->assignMultiple($assign);
    }

    /**
     * action delete
     *
     * @param \NITSAN\NsFaq\Domain\Model\Faq $faq
     * @return void
     */
    public function deleteAction(\NITSAN\NsFaq\Domain\Model\Faq $faq=null)
    {
        $heading = transalte::translate('deleteTitle', 'ns_faq');
        $msg = transalte::translate('deleteFaqmsg', 'ns_faq');
        $this->addFlashMessage($msg, $heading, \TYPO3\CMS\Core\Messaging\AbstractMessage::OK);

        if ($faq) {
            $this->faqRepository->remove($faq);
        } else {
            foreach (GeneralUtility::_GP('uids') as $uid) {
                $this->faqRepository->remove($this->faqRepository->findByUid($uid));
            }
        }
    }
}
