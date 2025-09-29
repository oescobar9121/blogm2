<?php
declare(strict_types=1);

namespace OEscobar\Blog\Test\Unit\Model;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use OEscobar\Blog\Api\Data\PostInterface;
use OEscobar\Blog\Model\Post;
use OEscobar\Blog\Model\PostFactory;
use OEscobar\Blog\Model\PostRepository;
use OEscobar\Blog\Model\ResourceModel\Post as PostResource;
use OEscobar\Blog\Model\ResourceModel\Post\Collection as PostCollection;
use OEscobar\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class PostRepositoryTest extends TestCase
{
    /**
     * @var PostResource|MockObject|(object&MockObject)|(PostResource&object&MockObject)|(PostResource&MockObject)|(MockObject&PostResource)
     */
    private PostResource&MockObject $resource;
    /**
     * @var PostFactory|MockObject|(object&MockObject)|(PostFactory&object&MockObject)|(PostFactory&MockObject)|(MockObject&PostFactory)
     */
    private PostFactory&MockObject $postFactory;
    /**
     * @var PostCollectionFactory|MockObject|(object&MockObject)|(PostCollectionFactory&object&MockObject)|(PostCollectionFactory&MockObject)|(MockObject&PostCollectionFactory)
     */
    private PostCollectionFactory&MockObject $collectionFactory;
    /**
     * @var SearchResultsInterfaceFactory|MockObject|(SearchResultsInterfaceFactory&object&MockObject)|(SearchResultsInterfaceFactory&MockObject)|(object&MockObject)
     */
    private SearchResultsInterfaceFactory&MockObject $searchResultsFactory;
    /**
     * @var CollectionProcessorInterface|MockObject|(CollectionProcessorInterface&object&MockObject)|(CollectionProcessorInterface&MockObject)|(object&MockObject)|(MockObject&CollectionProcessorInterface)
     */
    private CollectionProcessorInterface&MockObject $collectionProcessor;

    /**
     * @var PostRepository
     */
    private PostRepository $repo;

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        $this->resource = $this->createMock(PostResource::class);
        $this->postFactory = $this->createMock(PostFactory::class);
        $this->collectionFactory = $this->createMock(PostCollectionFactory::class);
        $this->searchResultsFactory = $this->createMock(SearchResultsInterfaceFactory::class);
        $this->collectionProcessor = $this->createMock(CollectionProcessorInterface::class);

        $this->repo = new PostRepository(
            $this->resource,
            $this->postFactory,
            $this->collectionFactory,
            $this->searchResultsFactory,
            $this->collectionProcessor
        );
    }

    /**
     * @return void
     * @throws NoSuchEntityException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetByIdReturnsPost(): void
    {
        $model = $this->createMock(Post::class);

        $this->postFactory
            ->expects(self::once())
            ->method('create')
            ->willReturn($model);

        $model->method('getId')->willReturn(123);

        $this->resource
            ->expects(self::once())
            ->method('load')
            ->with($model, 123);

        $result = $this->repo->getById(123);
        self::assertSame($model, $result);
    }

    /**
     * @return void
     * @throws NoSuchEntityException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetByIdThrowsIfNotFound(): void
    {
        $model = $this->createMock(Post::class);

        $this->postFactory
            ->expects(self::once())
            ->method('create')
            ->willReturn($model);

        $model->method('getId')->willReturn(null);

        $this->resource
            ->expects(self::once())
            ->method('load')
            ->with($model, 999);

        $this->expectException(NoSuchEntityException::class);
        $this->repo->getById(999);
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testSavePersistsModel(): void
    {
        $model = $this->createMock(PostInterface::class);

        $this->resource
            ->expects(self::once())
            ->method('save')
            ->with($model);

        $saved = $this->repo->save($model);
        self::assertSame($model, $saved);
    }

    /**
     * @return void
     * @throws NoSuchEntityException
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testDeleteByIdDeletes(): void
    {
        $id = 7;
        $model = $this->createMock(Post::class);

        $this->postFactory->method('create')->willReturn($model);
        $model->method('getId')->willReturn($id);

        $this->resource
            ->expects(self::once())
            ->method('load')
            ->with($model, $id);

        $this->resource
            ->expects(self::once())
            ->method('delete')
            ->with($model);

        $ok = $this->repo->deleteById($id);
        self::assertTrue($ok);
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    public function testGetListReturnsSearchResults(): void
    {
        $criteria = $this->createMock(SearchCriteriaInterface::class);
        $collection = $this->createMock(PostCollection::class);
        $searchResults = $this->createMock(SearchResultsInterface::class);

        $this->collectionFactory
            ->expects(self::once())
            ->method('create')
            ->willReturn($collection);

        $this->collectionProcessor
            ->expects(self::once())
            ->method('process')
            ->with($criteria, $collection);

        $collection->method('getItems')->willReturn([]);
        $collection->method('getSize')->willReturn(0);

        $this->searchResultsFactory
            ->expects(self::once())
            ->method('create')
            ->willReturn($searchResults);

        $searchResults->expects(self::once())->method('setItems')->with([]);
        $searchResults->expects(self::once())->method('setTotalCount')->with(0);
        $searchResults->expects(self::once())->method('setSearchCriteria')->with($criteria);

        $res = $this->repo->getList($criteria);
        self::assertSame($searchResults, $res);
    }
}
