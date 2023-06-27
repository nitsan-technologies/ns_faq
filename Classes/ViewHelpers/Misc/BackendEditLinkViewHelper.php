<?php
namespace NITSAN\NsFaq\ViewHelpers\Misc;

use NITSAN\NsFaq\Utility\BackendUtility;
//use TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * BackendEditLinkViewHelper
 *
 */
class BackendEditLinkViewHelper extends AbstractViewHelper
{
    public function initializeArguments()
    {
        $this->registerArgument('tableName', 'string', '', true);
        $this->registerArgument('identifier', 'integer', '', true);
    }

    /**
     * Create a link for backend edit
     *
     * @param string $tableName
     * @param int $identifier
     * @return string
     */
    public function render()
    {
        return BackendUtility::createEditUri($this->arguments['tableName'], $this->arguments['identifier'], true);
    }
}
