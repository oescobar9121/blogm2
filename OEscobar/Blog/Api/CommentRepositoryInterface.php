<?php
declare(strict_types=1);

namespace OEscobar\Blog\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use OEscobar\Blog\Api\Data\CommentInterface;

interface CommentRepositoryInterface
{
    /**
     * Retrieve Comment by ID.
     *
     * @param int $id
     * @return CommentInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getById(int $id): CommentInterface;

    /**
     * Persist Comment entity.
     *
     * @param CommentInterface $comment
     * @return CommentInterface
     * @throws LocalizedException
     */
    public function save(CommentInterface $comment): CommentInterface;

    /**
     * Delete Comment by ID.
     *
     * @param int $id
     * @return bool
     * @throws LocalizedException
     */
    public function deleteById(int $id): bool;

    /**
     * Retrieve Comments matching the specified criteria.
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return SearchResultsInterface
     *   Search results containing \OEscobar\Blog\Api\Data\CommentInterface items.
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria): SearchResultsInterface;
}
