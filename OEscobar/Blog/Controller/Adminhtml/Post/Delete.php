<?php

namespace OEscobar\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Redirect;
use OEscobar\Blog\Api\PostRepositoryInterface;

class Delete extends Action
{
    /**
     *
     */
    public const ADMIN_RESOURCE = 'OEscobar_Blog::posts';

    /**
     * @param Context $context
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        Context                                  $context,
        private readonly PostRepositoryInterface $postRepository
    )
    {
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = (int)$this->getRequest()->getParam('id');
        if (!$id) {
            $this->messageManager->addErrorMessage(__('We can\'t find a post to delete.'));
            return $resultRedirect->setPath('*/*/');
        }

        try {
            $this->postRepository->deleteById($id);
            $this->messageManager->addSuccessMessage(__('The post has been deleted.'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
        }

        return $resultRedirect->setPath('*/*/');
    }
}
