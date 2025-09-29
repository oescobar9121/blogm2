<?php
declare(strict_types=1);

namespace OEscobar\Blog\Block\Post;

use Magento\Framework\View\Element\Template;
use OEscobar\Blog\Model\PostFactory;
use OEscobar\Blog\Model\ResourceModel\Post as PostResource;
use OEscobar\Blog\Model\ResourceModel\Comment\CollectionFactory as CommentCollectionFactory;
use Magento\Framework\Data\Form\FormKey;

class View extends Template
{
    /**
     * @param Template\Context $context
     * @param PostFactory $postFactory
     * @param PostResource $postResource
     * @param CommentCollectionFactory $commentCollectionFactory
     * @param FormKey $formKey
     * @param array $data
     */
    public function __construct(
        Template\Context                          $context,
        private readonly PostFactory              $postFactory,
        private readonly PostResource             $postResource,
        private readonly CommentCollectionFactory $commentCollectionFactory,
        private readonly FormKey                  $formKey,
        array                                     $data = []
    )
    {
        parent::__construct($context, $data);
    }

    /**
     * @return \OEscobar\Blog\Model\Post|null
     */
    public function getPost()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $model = $this->postFactory->create();
        if ($id) {
            $this->postResource->load($model, $id);
        }
        return $model->getId() ? $model : null;
    }

    /**
     * @return array|\OEscobar\Blog\Model\ResourceModel\Comment\Collection
     */
    public function getComments()
    {
        $post = $this->getPost();
        if (!$post) return [];
        return $this->commentCollectionFactory->create()
            ->addFieldToFilter('post_id', (int)$post->getId())
            ->addFieldToFilter('status', 1)
            ->setOrder('created_at', 'DESC');
    }

    /**
     * @return string
     */
    public function getFormAction(): string
    {
        $post = $this->getPost();
        return $post ? $this->getUrl('blog/comment/submit', ['post_id' => (int)$post->getId()]) : '#';
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getFormKey(): string
    {
        return $this->formKey->getFormKey();
    }

    /**
     * @return $this|View
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $post = $this->getPost();
        if ($post) {
            $title = (string)$post->getTitle();
            $this->pageConfig->getTitle()->set($title);
            $this->pageConfig->setDescription(mb_substr((string)$post->getContent(), 0, 160));
            $this->pageConfig->addRemotePageAsset(
                $this->getUrl('blog/post/view', ['id' => (int)$post->getId()]),
                'canonical',
                ['attributes' => ['rel' => 'canonical']]
            );
        } else {
            $this->pageConfig->getTitle()->set(__('Post not found'));
        }
        return $this;
    }

}
