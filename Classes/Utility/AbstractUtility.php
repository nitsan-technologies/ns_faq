<?php

namespace NITSAN\NsFaq\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

/**
 * Class AbstractUtility
 *
 */
abstract class AbstractUtility
{
    /**
    * @return ContentObjectRenderer
    */
    protected static function getContentObject()
    {
        return self::getObjectManager()->get(ContentObjectRenderer::class);
    }


    /**
     * @return ObjectManager
     */
    protected static function getObjectManager()
    {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}
