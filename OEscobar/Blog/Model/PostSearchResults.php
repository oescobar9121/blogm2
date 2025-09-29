<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model;

use Magento\Framework\Api\SearchResults;
use OEscobar\Blog\Api\Data\PostSearchResultsInterface;

class PostSearchResults extends SearchResults implements PostSearchResultsInterface
{
}
