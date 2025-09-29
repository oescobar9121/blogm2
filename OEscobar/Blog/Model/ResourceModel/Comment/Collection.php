<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model\ResourceModel\Comment;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use OEscobar\Blog\Model\Comment as CommentModel;
use OEscobar\Blog\Model\ResourceModel\Comment as CommentResource;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'comment_id';

    protected function _construct(): void
    {
        $this->_init(CommentModel::class, CommentResource::class);
    }
}
