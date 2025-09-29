<?php
declare(strict_types=1);

namespace OEscobar\Blog\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use OEscobar\Blog\Model\CommentFactory;
use OEscobar\Blog\Model\ResourceModel\Comment as CommentResource;

class Unapprove extends Action
{
    public const ADMIN_RESOURCE = 'OEscobar_Blog::posts';

    public function __construct(
        Context                 $context,
        private CommentFactory  $commentFactory,
        private CommentResource $commentResource
    )
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $backId = (int)$this->getRequest()->getParam('back_id');

        if ($id <= 0) {
            $this->messageManager->addErrorMessage(__('Invalid comment id.'));
            return $this->resultRedirectFactory->create()->setPath('oescobar_blog/post/index');
        }

        try {
            $comment = $this->commentFactory->create();
            $this->commentResource->load($comment, $id);
            if (!$comment->getId()) {
                throw new \RuntimeException('Comment not found.');
            }
            $comment->setStatus(0);
            $this->commentResource->save($comment);
            $this->messageManager->addSuccessMessage(__('Comment unapproved.'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Could not unapprove: %1', $e->getMessage()));
        }

        if ($backId > 0) {
            return $this->resultRedirectFactory->create()->setPath('oescobar_blog/post/view', ['id' => $backId]);
        }
        return $this->resultRedirectFactory->create()->setPath('oescobar_blog/post/index');
    }
}
