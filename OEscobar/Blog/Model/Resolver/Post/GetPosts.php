<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model\Resolver\Post;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use OEscobar\Blog\Api\PostRepositoryInterface;

class GetPosts implements ResolverInterface
{
    /**
     * @param PostRepositoryInterface $postRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        private readonly PostRepositoryInterface $postRepository,
        private readonly SearchCriteriaBuilder   $searchCriteriaBuilder,
        private readonly FilterBuilder           $filterBuilder
    )
    {
    }

    /**
     * @param $field
     * @param $context
     * @param ResolveInfo $info
     * @param array|null $value
     * @param array|null $args
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function resolve($field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        $pageSize = isset($args['pageSize']) ? max(1, (int)$args['pageSize']) : 20;
        $currentPage = isset($args['currentPage']) ? max(1, (int)$args['currentPage']) : 1;

        $this->searchCriteriaBuilder->setPageSize($pageSize)->setCurrentPage($currentPage);

        if (!empty($args['search'])) {
            $filters = [
                $this->filterBuilder->setField('title')->setValue('%' . $args['search'] . '%')->setConditionType('like')->create(),
                $this->filterBuilder->setField('content')->setValue('%' . $args['search'] . '%')->setConditionType('like')->create()
            ];
            $this->searchCriteriaBuilder->addFilters($filters);
        }

        $criteria = $this->searchCriteriaBuilder->create();
        $list = $this->postRepository->getList($criteria);

        $items = [];
        foreach ($list->getItems() as $post) {
            $items[] = [
                'post_id' => $post->getId(),
                'title' => $post->getTitle(),
                'content' => $post->getContent(),
                'author' => $post->getAuthor(),
                'status' => $post->getStatus(),
                'created_at' => $post->getCreatedAt(),
                'updated_at' => $post->getUpdatedAt()
            ];
        }

        return [
            'items' => $items,
            'total_count' => $list->getTotalCount()
        ];
    }
}
