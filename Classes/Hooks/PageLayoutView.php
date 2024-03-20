<?php

namespace NITSAN\NsFaq\Hooks;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Fluid\View\StandaloneView;
use TYPO3\CMS\Core\Service\FlexFormService;

class PageLayoutView implements \TYPO3\CMS\Backend\View\PageLayoutViewDrawItemHookInterface
{
    public function preProcess(
        \TYPO3\CMS\Backend\View\PageLayoutView &$parentObject,
        &$drawItem,
        &$headerContent,
        &$itemContent,
        array &$row
    ) {
        $extKey = 'ns_faq';
        if ($row['CType'] == 'list' && $row['list_type'] == 'nsfaq_faq') {
            $drawItem = false;
            $headerContent = '';

            $view = $this->getFluidTemplate($extKey, 'NsFaq');
            if (!empty($row['pi_flexform'])) {
                /** @var FlexFormService $flexFormService */
                if (version_compare(TYPO3_branch, '9.0', '>')) {
                    $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
                } else {
                    $flexFormService = GeneralUtility::makeInstance(FlexFormService::class);
                }
            }

            // assign all to view
            $view->assignMultiple([
                'flexformData' => $flexFormService->convertFlexFormContentToArray($row['pi_flexform']),
            ]);

            // return the preview
            $itemContent = $parentObject->linkEditContent($view->render(), $row);
        }
    }

    /**
     * @param string $extKey
     * @param string $templateName
     * @return object the fluid template
     */
    protected function getFluidTemplate($extKey, $templateName)
    {
        // prepare own template
        $fluidTemplateFile = GeneralUtility::getFileAbsFileName('EXT:' . $extKey . '/Resources/Private/Backend/'
            . $templateName . '.html');
        $view = GeneralUtility::makeInstance(StandaloneView::class);
        $view->setTemplatePathAndFilename($fluidTemplateFile);
        return $view;
    }
}
