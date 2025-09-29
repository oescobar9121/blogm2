<?php
declare(strict_types=1);

namespace OEscobar\Blog\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;
use OEscobar\Blog\Model\ResourceModel\Comment\CollectionFactory;
use OEscobar\Blog\Model\ResourceModel\Comment as CommentResource;

class MassApprove extends Action
{
    /**
     *
     */
    public const ADMIN_RESOURCE = 'OEscobar_Blog::posts';

    /**
     * @param Action\Context $context
     * @param CollectionFactory $collectionFactory
     * @param CommentResource $commentResource
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        private readonly CollectionFactory  $collectionFactory,
        private readonly CommentResource    $commentResource
    )
    {
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function execute()
    {
        $ids = (array)$this->getRequest()->getParam('selected', []);
        if (!$ids) {
            $this->messageManager->addErrorMessage(__('Please select comment(s).'));
            return $this->_redirect($this->_redirect->getRefererUrl());
        }
        $count = 0;
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter('comment_id', ['in' => $ids]);
        foreach ($collection as $item) {
            $item->setData('status', 1);
            $this->commentResource->save($item);
            $count++;
        }
        $this->messageManager->addSuccessMessage(__('Approved %1 comment(s).', $count));
        return $this->_redirect($this->_redirect->getRefererUrl());
    }
}
