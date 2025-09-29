<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model\Resolver\Post;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use OEscobar\Blog\Api\CommentRepositoryInterface;

class Comments implements ResolverInterface
{
    /**
     * @param CommentRepositoryInterface $commentRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        private readonly CommentRepositoryInterface $commentRepository,
        private readonly SearchCriteriaBuilder      $searchCriteriaBuilder,
        private readonly FilterBuilder              $filterBuilder
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
        $postId = (int)$value['post_id'];

        $pageSize = isset($args['pageSize']) ? max(1, (int)$args['pageSize']) : 20;
        $currentPage = isset($args['currentPage']) ? max(1, (int)$args['currentPage']) : 1;

        $filter = $this->filterBuilder->setField('post_id')->setValue($postId)->setConditionType('eq')->create();
        $this->searchCriteriaBuilder->addFilters([$filter])->setPageSize($pageSize)->setCurrentPage($currentPage);

        $criteria = $this->searchCriteriaBuilder->create();
        $list = $this->commentRepository->getList($criteria);

        $items = [];
        foreach ($list->getItems() as $c) {
            $items[] = [
                'comment_id' => $c->getId(),
                'post_id' => $c->getPostId(),
                'content' => $c->getContent(),
                'author' => $c->getAuthor(),
                'status' => $c->getStatus(),
                'created_at' => $c->getCreatedAt()
            ];
        }

        return [
            'items' => $items,
            'total_count' => $list->getTotalCount()
        ];
    }
}
