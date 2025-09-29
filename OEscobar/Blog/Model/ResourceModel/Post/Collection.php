<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model\ResourceModel\Post;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use OEscobar\Blog\Model\Post as PostModel;
use OEscobar\Blog\Model\ResourceModel\Post as PostResource;

class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'post_id';

    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init(PostModel::class, PostResource::class);
    }
}
