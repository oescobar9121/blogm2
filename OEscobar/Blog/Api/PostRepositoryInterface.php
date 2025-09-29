<?php
declare(strict_types=1);

namespace OEscobar\Blog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use OEscobar\Blog\Api\Data\PostInterface;

interface PostRepositoryInterface
{
    /**
     * Retrieve Post by ID.
     *
     * @param int $id
     * @return PostInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getById(int $id): PostInterface;

    /**
     * Persist Post entity.
     *
     * @param PostInterface $post
     * @return PostInterface
     * @throws LocalizedException
     */
    public function save(PostInterface $post): PostInterface;

    /**
     * Delete Post by ID.
     *
     * @param int $id
     * @return bool
     * @throws LocalizedException
     */
    public function deleteById(int $id): bool;

    /**
     * Retrieve Posts matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     *   Search results containing \OEscobar\Blog\Api\Data\PostInterface items.
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
