<?php
declare(strict_types=1);

namespace OEscobar\Blog\Test\Unit\Model\Resolver\Post;

use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use OEscobar\Blog\Api\PostRepositoryInterface;
use OEscobar\Blog\Model\Resolver\Post\GetPost;
use OEscobar\Blog\Api\Data\PostInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GetPostTest extends TestCase
{
    /**
     * @var PostRepositoryInterface|MockObject|(object&MockObject)|(PostRepositoryInterface&object&MockObject)|(PostRepositoryInterface&MockObject)
     */
    private PostRepositoryInterface&MockObject $repo;
    /**
     * @var GetPost
     */
    private GetPost $resolver;

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->repo = $this->createMock(PostRepositoryInterface::class);
        $this->resolver = new GetPost($this->repo);
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testResolveReturnsArrayShape(): void
    {
        $post = $this->createMock(PostInterface::class);
        $post->method('getId')->willReturn(1);
        $post->method('getTitle')->willReturn('Hello');
        $post->method('getContent')->willReturn('World');
        $post->method('getAuthor')->willReturn('System');
        $post->method('getStatus')->willReturn(1);
        $post->method('getCreatedAt')->willReturn('2025-01-01 00:00:00');
        $post->method('getUpdatedAt')->willReturn('2025-01-01 00:00:00');

        $this->repo->method('getById')->with(1)->willReturn($post);

        $args = ['id' => 1];
        $resolved = $this->resolver->resolve(
            field: null,
            context: null,
            info: $this->createMock(ResolveInfo::class),
            value: null,
            args: $args
        );

        self::assertSame(1, $resolved['post_id']);
        self::assertSame('Hello', $resolved['title']);
        self::assertSame('World', $resolved['content']);
    }
}
