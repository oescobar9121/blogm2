<?php
declare(strict_types=1);

namespace OEscobar\Blog\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CommentSearchResultsInterface extends SearchResultsInterface
{
    /** @return \OEscobar\Blog\Api\Data\CommentInterface[] */
    public function getItems();

    /** @param \OEscobar\Blog\Api\Data\CommentInterface[] $items */
    public function setItems(array $items);
}
