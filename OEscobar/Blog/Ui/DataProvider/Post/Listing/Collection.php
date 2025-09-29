<?php
declare(strict_types=1);

namespace OEscobar\Blog\Ui\DataProvider\Post\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * @return void
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('post_id', 'main_table.post_id');
        $this->addFilterToMap('title', 'main_table.title');
        $this->addFilterToMap('author', 'main_table.author');
        $this->addFilterToMap('status', 'main_table.status');
        $this->addFilterToMap('created_at', 'main_table.created_at');
        $this->addFilterToMap('updated_at', 'main_table.updated_at');
        parent::_initSelect();
    }
}
