<?php
declare(strict_types=1);

namespace OEscobar\Blog\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use OEscobar\Blog\Model\CommentFactory;
use OEscobar\Blog\Model\ResourceModel\Comment as CommentResource;

class Approve extends Action
{
    /**
     *
     */
    public const ADMIN_RESOURCE = 'OEscobar_Blog::posts';

    /**
     * @param Action\Context $context
     * @param CommentFactory $commentFactory
     * @param CommentResource $commentResource
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private readonly CommentFactory     $commentFactory,
        private readonly CommentResource    $commentResource
    )
    {
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam('id');
        $backId = (int)$this->getRequest()->getParam('back_id', (int)$this->getRequest()->getParam('post_id', 0));

        if ($id <= 0) {
            $this->messageManager->addErrorMessage(__('Invalid comment.'));
            return $this->_redirect('oescobar_blog/post/index');
        }

        try {
            $model = $this->commentFactory->create();
            $this->commentResource->load($model, $id);
            if (!$model->getId()) {
                throw new \RuntimeException('Comment not found.');
            }
            $model->setData('status', 1);
            $this->commentResource->save($model);
            $this->messageManager->addSuccessMessage(__('Comment approved.'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Error: %1', $e->getMessage()));
        }

        if ($backId > 0) {
            return $this->_redirect('oescobar_blog/post/view', ['id' => $backId]);
        }
        return $this->_redirect('oescobar_blog/post/index');
    }
}
