<?php
declare(strict_types=1);

namespace OEscobar\Blog\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface PostSearchResultsInterface extends SearchResultsInterface
{
    /** @return \OEscobar\Blog\Api\Data\PostInterface[] */
    public function getItems();
    /** @param \OEscobar\Blog\Api\Data\PostInterface[] $items */
    public function setItems(array $items);
}
