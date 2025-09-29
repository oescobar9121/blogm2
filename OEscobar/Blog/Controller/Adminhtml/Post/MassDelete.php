<?php
declare(strict_types=1);

namespace OEscobar\Blog\Controller\Adminhtml\Post;

use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use OEscobar\Blog\Model\ResourceModel\Post\CollectionFactory;
use OEscobar\Blog\Api\PostRepositoryInterface;

class MassDelete extends Action
{
    /**
     *
     */
    public const ADMIN_RESOURCE = 'OEscobar_Blog::posts';

    /**
     * @param Action\Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        Action\Context                           $context,
        private readonly Filter                  $filter,
        private readonly CollectionFactory       $collectionFactory,
        private readonly PostRepositoryInterface $postRepository
    )
    {
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $count = 0;
        foreach ($collection as $post) {
            $this->postRepository->deleteById((int)$post->getId());
            $count++;
        }
        $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $count));
        return $this->resultRedirectFactory->create()->setPath('*/*/');
    }
}
