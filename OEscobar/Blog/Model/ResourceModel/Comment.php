<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Comment extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct(): void
    {
        $this->_init('oescobar_blog_comment', 'comment_id');
    }
}
