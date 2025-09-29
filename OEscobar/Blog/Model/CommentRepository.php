<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use OEscobar\Blog\Api\CommentRepositoryInterface;
use OEscobar\Blog\Api\Data\CommentInterface;
use OEscobar\Blog\Model\ResourceModel\Comment as CommentResource;
use OEscobar\Blog\Model\ResourceModel\Comment\CollectionFactory as CommentCollectionFactory;

class CommentRepository implements CommentRepositoryInterface
{
    /**
     * @param CommentResource $resource
     * @param CommentFactory $commentFactory
     * @param CommentCollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param CollectionProcessorInterface $collectionProcessor
     */
    public function __construct(
        private readonly CommentResource               $resource,
        private readonly CommentFactory                $commentFactory,
        private readonly CommentCollectionFactory      $collectionFactory,
        private readonly SearchResultsInterfaceFactory $searchResultsFactory,
        private readonly CollectionProcessorInterface  $collectionProcessor
    )
    {
    }

    /**
     * @param int $id
     * @return CommentInterface
     * @throws NoSuchEntityException
     */
    public function getById(int $id): CommentInterface
    {
        $comment = $this->commentFactory->create();
        $this->resource->load($comment, $id);
        if (!$comment->getId()) {
            throw new NoSuchEntityException(__('Comment with ID %1 does not exist.', $id));
        }
        return $comment;
    }

    /**
     * @param CommentInterface $comment
     * @return CommentInterface
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     */
    public function save(CommentInterface $comment): CommentInterface
    {
        $this->resource->save($comment);
        return $comment;
    }

    /**
     * @param int $id
     * @return bool
     * @throws NoSuchEntityException
     */
    public function deleteById(int $id): bool
    {
        $comment = $this->getById($id);
        $this->resource->delete($comment);
        return true;
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): \Magento\Framework\Api\SearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $results = $this->searchResultsFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $results->setItems($collection->getItems());
        $results->setTotalCount((int)$collection->getSize());
        $results->setSearchCriteria($searchCriteria);
        return $results;
    }
}
