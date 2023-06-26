<?php

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

namespace NITSAN\NsFaq\ViewHelpers\Be;

use TYPO3\CMS\Backend\Routing\UriBuilder;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Type\Bitmask\Permission;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Backend\RecordList\DatabaseRecordList;
use TYPO3\CMS\Core\Localization\LanguageService;
use TYPO3\CMS\Core\FormProtection\FormProtectionFactory;
use TYPO3\CMS\Core\Authentication\BackendUserAuthentication;
use TYPO3\CMS\Fluid\ViewHelpers\Be\AbstractBackendViewHelper;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * ViewHelper which renders a record list as known from the TYPO3 list module.
 *
 * .. note::
 *    This feature is experimental!
 *
 * Examples
 * ========
 *
 * Minimal::
 *
 *    <f:be.tableList tableName="fe_users" />
 *
 * List of all "Website user" records stored in the configured storage PID.
 * Records will be editable, if the current backend user has got edit rights for the table ``fe_users``.
 *
 * Only the title column (username) will be shown.
 *
 * Context menu is active.
 *
 * 
 * Full::
 *
 *    <f:be.tableList tableName="fe_users" fieldList="{0: 'name', 1: 'email'}"
 *        storagePid="1"
 *        levels="2"
 *        filter="foo"
 *        recordsPerPage="10"
 *        sortField="name"
 *        sortDescending="true"
 *        readOnly="true"
 *        enableClickMenu="false"
 *        enableControlPanels="true"
 *        clickTitleMode="info"
 *        />
 *
 * List of "Website user" records with a text property of ``foo`` stored on PID ``1`` and two levels down.
 * Clicking on a username will open the TYPO3 info popup for the respective record
 */
class TableListViewHelper extends AbstractBackendViewHelper
{

	protected ServerRequestInterface $request;

	public function setRequest(ServerRequestInterface $request): void
	{
		$this->request = $request;
	}

	/**
	 * As this ViewHelper renders HTML, the output must not be escaped.
	 *
	 * @var bool
	 */
	protected $escapeOutput = false;

	/**
	 * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
	 */
	protected $configurationManager;

	/**
	 * @param \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface $configurationManager
	 */
	public function injectConfigurationManager(ConfigurationManagerInterface $configurationManager): void
	{
		$this->configurationManager = $configurationManager;
	}

	/**
	 * Initialize arguments.
	 *
	 * @throws \TYPO3Fluid\Fluid\Core\ViewHelper\Exception
	 */
	public function initializeArguments(): void
	{
		parent::initializeArguments();
		$this->registerArgument('tableName', 'string', 'name of the database table', true);
		$this->registerArgument('fieldList', 'array', 'list of fields to be displayed. If empty, only the title column (configured in $TCA[$tableName][\'ctrl\'][\'title\']) is shown', false, []);
		$this->registerArgument('storagePid', 'int', 'by default, records are fetched from the storage PID configured in persistence.storagePid. With this argument, the storage PID can be overwritten');
		$this->registerArgument('levels', 'int', 'corresponds to the level selector of the TYPO3 list module. By default only records from the current storagePid are fetched', false, 0);
		$this->registerArgument('filter', 'string', 'corresponds to the "Search String" textbox of the TYPO3 list module. If not empty, only records matching the string will be fetched', false, '');
		$this->registerArgument('recordsPerPage', 'int', 'amount of records to be displayed at once. Defaults to $TCA[$tableName][\'interface\'][\'maxSingleDBListItems\'] or (if that\'s not set) to 100', false, 0);
		$this->registerArgument('sortField', 'string', 'table field to sort the results by', false, '');
		$this->registerArgument('sortDescending', 'bool', 'if TRUE records will be sorted in descending order', false, false);
		$this->registerArgument('readOnly', 'bool', 'if TRUE, the edit icons won\'t be shown. Otherwise edit icons will be shown, if the current BE user has edit rights for the specified table!', false, false);
		$this->registerArgument('enableClickMenu', 'bool', 'enables context menu', false, true);
		$this->registerArgument('enableControlPanels', 'bool', 'enables control panels', false, false);
		$this->registerArgument('clickTitleMode', 'string', 'one of "edit", "show" (only pages, tt_content), "info');
	}

	/**
	 * Renders a record list as known from the TYPO3 list module
	 * Note: This feature is experimental!
	 *
	 * @return string the rendered record list
	 * @throws \TYPO3\CMS\Core\Type\Exception\InvalidEnumerationValueException
	 * @see \TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList
	 */
	public function render()
	{
		$tableName = $this->arguments['tableName'];
		$fieldList = $this->arguments['fieldList'];
		$storagePid = $this->arguments['storagePid'];
		$levels = $this->arguments['levels'];
		$filter = $this->arguments['filter'];
		$recordsPerPage = $this->arguments['recordsPerPage'];
		$sortField = $this->arguments['sortField'];
		$sortDescending = $this->arguments['sortDescending'];
		$readOnly = $this->arguments['readOnly'];
		$enableClickMenu = $this->arguments['enableClickMenu'];
		$clickTitleMode = $this->arguments['clickTitleMode'];
		$enableControlPanels = $this->arguments['enableControlPanels'];

		$this->getLanguageService()->includeLLFile('EXT:core/Resources/Private/Language/locallang_mod_web_list.xlf');
		$backendUser = $this->getBackendUser();

		$renderingContext = $this->renderingContext;
		$request = $renderingContext->getRequest();
		if (!$request instanceof ServerRequestInterface) {
			// All views in backend have at least ServerRequestInterface, no matter if created by
			// old StandaloneView via BackendViewFactory. Should be fine to assume having a request
			// here, the early return is just sanitation.
			return '';
		}

		$this->getPageRenderer()->loadJavaScriptModule('@typo3/backend/recordlist.js');
		$this->getPageRenderer()->loadJavaScriptModule('@nitsan/ns-faq/ajax-data-handler.js');
		$this->getPageRenderer()->loadJavaScriptModule('@typo3/backend/record-download-button.js');
		$this->getPageRenderer()->loadJavaScriptModule('@typo3/backend/action-dispatcher.js');
		if ($enableControlPanels === true) {
			$this->getPageRenderer()->loadJavaScriptModule('@typo3/backend/multi-record-selection.js');
			//            $this->getPageRenderer()->loadJavaScriptModule('@typo3/backend/context-menu.js');
		}

		$pageId = (int)($request->getParsedBody()['id'] ?? $request->getQueryParams()['id'] ?? 0);
		$pointer = (int)($request->getParsedBody()['pointer'] ?? $request->getQueryParams()['pointer'] ?? 0);
		$pageInfo = BackendUtility::readPageAccess($pageId, $backendUser->getPagePermsClause(Permission::PAGE_SHOW)) ?: [];
		$dbList = GeneralUtility::makeInstance(DatabaseRecordList::class);
		$dbList->setRequest($request);
		$dbList->pageRow = $pageInfo;
		if ($readOnly) {
			$dbList->setIsEditable(false);
		} else {
			$dbList->calcPerms = new Permission($backendUser->calcPerms($pageInfo));
		}
		$dbList->disableSingleTableView = true;
		$dbList->clickTitleMode = $clickTitleMode;
		$dbList->clickMenuEnabled = $enableClickMenu;
		if ($storagePid === null) {
			$frameworkConfiguration = $this->configurationManager->getConfiguration(ConfigurationManagerInterface::CONFIGURATION_TYPE_FRAMEWORK);
			$storagePid = $frameworkConfiguration['persistence']['storagePid'];
		}
		$dbList->start($storagePid, $tableName, $pointer, $filter, $levels, $recordsPerPage);
		$dbList->displayColumnSelector = false;
		$dbList->setFields = [$tableName => $fieldList];
		$dbList->noControlPanels = !$enableControlPanels;
		$dbList->sortField = $sortField;
		$dbList->sortRev = $sortDescending;
		$html = $dbList->generateList();

		if (empty($html)) {
			$html = '
				<div class="alert alert-warning" role="alert">
				  <h4 class="alert-heading">Opps!</h4>
				  <p>There are no any records are found!</p>
				  <hr>
				  <p class="mb-0">To create a new record please click <a class="alert-link" href="' . $this->redirectToCreateNewRecord('tx_nsfaq_domain_model_faq') . '">Add new</a></p>
				</div>';
		}
		return $html;
	}

	protected function getLanguageService(): LanguageService
	{
		return $GLOBALS['LANG'];
	}

	/**
	 * Redirect to tceform creating a new record
	 *
	 * @param string $table table name
	 */
	private function redirectToCreateNewRecord($table): string
	{
		$pid = (int) \TYPO3\CMS\Core\Utility\GeneralUtility::_GET('id');
		$returnUrl = GeneralUtility::getIndpEnv('REQUEST_URI');
		$url = $this->getModuleUrl('record_edit', [
			'edit[' . $table . '][' . $pid . ']' => 'new',
			'returnUrl' => $returnUrl,
		]);
		return $url;
	}

	/**
	 * Get a CSRF token
	 *
	 * @param bool $tokenOnly Set it to TRUE to get only the token, otherwise including the &moduleToken= as prefix
	 * @return string
	 */
	protected function getToken(bool $tokenOnly = false): string
	{

		$tokenParameterName = 'token';
		$token = FormProtectionFactory::get('backend')->generateToken('route', 'nitsan_NsFaqFaqbackend');
		if ($tokenOnly) {
			return $token;
		}

		return '&' . $tokenParameterName . '=' . $token;
	}
	/**
	 * Returns the URL to a given module mainly used for visibility settings or deleting a record via AJAX
	 * @param string $moduleName Name of the module
	 * @param array $urlParameters URL parameters that should be added as key value pairs
	 * @return string Calculated URL
	 */
	public static function getModuleUrl($moduleName, $urlParameters = [])
	{
		$uriBuilder = GeneralUtility::makeInstance(UriBuilder::class);
		return $uriBuilder->buildUriFromRoute($moduleName, $urlParameters);
	}

	protected function getBackendUser(): BackendUserAuthentication
	{
		return $GLOBALS['BE_USER'];
	}
}
