<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Post extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('oescobar_blog_post', 'post_id');
    }
}
