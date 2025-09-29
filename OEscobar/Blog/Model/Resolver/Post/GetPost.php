<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model\Resolver\Post;

use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use OEscobar\Blog\Api\PostRepositoryInterface;

class GetPost implements ResolverInterface
{
    /**
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        private readonly PostRepositoryInterface $postRepository
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
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function resolve(
        $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        /** @var ContextInterface $context */
        $id = (int)$args['id'];
        $post = $this->postRepository->getById($id);

        return [
            'post_id' => $post->getId(),
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'author' => $post->getAuthor(),
            'status' => $post->getStatus(),
            'created_at' => $post->getCreatedAt(),
            'updated_at' => $post->getUpdatedAt()
        ];
    }
}
