<?php

namespace NITSAN\NsFaq\Domain\Model;

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
 * Faq
 */
class Faq extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * faqTitle
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $faqTitle = '';

    /**
     * faqContent
     *
     * @var string
     * @TYPO3\CMS\Extbase\Annotation\Validate("NotEmpty")
     */
    protected $faqContent = '';

    /**
     * Returns the faqTitle
     *
     * @return string $faqTitle
     */
    public function getFaqTitle()
    {
        return $this->faqTitle;
    }

    /**
     * Sets the faqTitle
     *
     * @param string $faqTitle
     * @return void
     */
    public function setFaqTitle($faqTitle)
    {
        $this->faqTitle = $faqTitle;
    }

    /**
     * Returns the faqContent
     *
     * @return string $faqContent
     */
    public function getFaqContent()
    {
        return $this->faqContent;
    }

    /**
     * Sets the faqContent
     *
     * @param string $faqContent
     * @return void
     */
    public function setFaqContent($faqContent)
    {
        $this->faqContent = $faqContent;
    }
}
