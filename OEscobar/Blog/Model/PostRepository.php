<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model;

use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use OEscobar\Blog\Api\Data\PostInterface;
use OEscobar\Blog\Api\Data\PostInterfaceFactory;               // DTO factory
use OEscobar\Blog\Api\Data\PostSearchResultsInterface;
use OEscobar\Blog\Api\Data\PostSearchResultsInterfaceFactory;  // SearchResults DTO
use OEscobar\Blog\Api\PostRepositoryInterface;
use OEscobar\Blog\Model\ResourceModel\Post as PostResource;
use OEscobar\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class PostRepository implements PostRepositoryInterface
{
    public function __construct(
        private readonly PostResource                      $resource,
        private readonly PostFactory                       $postFactory,          // Model (BD)
        private readonly PostCollectionFactory             $collectionFactory,    // Model collection
        private readonly PostInterfaceFactory              $postDataFactory,      // DTO
        private readonly DataObjectHelper                  $dataObjectHelper,
        private readonly PostSearchResultsInterfaceFactory $searchResultsFactory, // DTO
        private readonly CollectionProcessorInterface      $collectionProcessor
    ) {}

    public function getById(int $id): PostInterface
    {
        $model = $this->postFactory->create();
        $this->resource->load($model, $id);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Post with ID %1 does not exist.', $id));
        }
        return $this->toDataObject($model);
    }

    public function save(PostInterface $post): PostInterface
    {
        $model = $this->postFactory->create();
        // mapeo simple: asumiendo mismas keys (ID, TITLE, CONTENT, AUTHOR, STATUS, CREATED_AT, UPDATED_AT)
        $model->setData((array)$post);
        $this->resource->save($model);
        return $this->getById((int)$model->getId());
    }

    public function deleteById(int $id): bool
    {
        $model = $this->postFactory->create();
        $this->resource->load($model, $id);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Post with ID %1 does not exist.', $id));
        }
        $this->resource->delete($model);
        return true;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): PostSearchResultsInterface
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setTotalCount((int)$collection->getSize());

        $items = [];
        foreach ($collection->getItems() as $model) {
            $items[] = $this->toDataObject($model);
        }
        $searchResults->setItems($items);

        return $searchResults;
    }

    private function toDataObject(\OEscobar\Blog\Model\Post $model): PostInterface
    {
        $data = $this->postDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $data,
            $model->getData(),
            PostInterface::class
        );
        return $data;
    }
}
