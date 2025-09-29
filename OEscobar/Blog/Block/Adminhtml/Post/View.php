<?php
declare(strict_types=1);

namespace OEscobar\Blog\Block\Adminhtml\Post;

use Magento\Backend\Block\Template;
use OEscobar\Blog\Model\PostFactory;
use OEscobar\Blog\Model\ResourceModel\Post as PostResource;

class View extends Template
{
    public function __construct(
        Template\Context              $context,
        private readonly PostFactory  $postFactory,
        private readonly PostResource $postResource,
        array                         $data = []
    )
    {
        parent::__construct($context, $data);
    }

    public function getPost(): ?\OEscobar\Blog\Model\Post
    {
        $id = (int)$this->getRequest()->getParam('id');
        if ($id <= 0) return null;
        $model = $this->postFactory->create();
        $this->postResource->load($model, $id);
        return $model->getId() ? $model : null;
    }
}
