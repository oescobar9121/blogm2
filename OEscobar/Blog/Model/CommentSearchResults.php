<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model;

use Magento\Framework\Api\SearchResults;
use OEscobar\Blog\Api\Data\CommentSearchResultsInterface;

class CommentSearchResults extends SearchResults implements CommentSearchResultsInterface
{
}
