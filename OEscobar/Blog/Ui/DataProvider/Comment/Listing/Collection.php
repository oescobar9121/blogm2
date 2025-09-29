<?php
declare(strict_types=1);

namespace OEscobar\Blog\Ui\DataProvider\Comment\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * @return void
     */
    protected function _initSelect()
    {
        $this->addFilterToMap('comment_id', 'main_table.comment_id');
        $this->addFilterToMap('post_id', 'main_table.post_id');
        $this->addFilterToMap('content', 'main_table.content');
        $this->addFilterToMap('author', 'main_table.author');
        $this->addFilterToMap('status', 'main_table.status');
        $this->addFilterToMap('created_at', 'main_table.created_at');
        parent::_initSelect();
    }
}
