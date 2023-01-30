<?php
namespace NITSAN\NsFaq\RecordList;

class DatabaseRecordList extends \TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList
{
    /**
     * Creates a page browser for tables with many records
     *
     * @param string $table
     * @param int $totalItems
     * @param int $itemsPerPage
     * @return string Navigation HTML
     */
    protected function renderListNavigation(string $table, int $totalItems, int $itemsPerPage): string
    {
        $currentPage = $this->page;
        $paginationColumns = count($this->fieldArray);
        $totalPages = (int)ceil($totalItems / $itemsPerPage);
        // Show page selector if not all records fit into one page
        if ($totalPages <= 1) {
            return '';
        }
        if ($totalItems > $currentPage * $itemsPerPage) {
            $lastElementNumber = $currentPage * $itemsPerPage;
        } else {
            $lastElementNumber = $totalItems;
        }
        return $this->getFluidTemplateObject('ListNavigation.html')
            ->assignMultiple([
                'currentUrl' => $this->listURL('', $table, 'pointer').'&tx_nsfaq_nitsan_nsfaqfaqbackend[action]=faqList&tx_nsfaq_nitsan_nsfaqfaqbackend[controller]=FaqModule',
                'currentPage' => $currentPage,
                'totalPages' => $totalPages,
                'firstElement' => ((($currentPage -1) * $itemsPerPage) + 1),
                'lastElement' => $lastElementNumber,
                'colspan' => $paginationColumns,
            ])
            ->render();
    }
}