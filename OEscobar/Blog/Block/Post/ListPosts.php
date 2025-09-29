<?php
declare(strict_types=1);

namespace OEscobar\Blog\Block\Post;

use Magento\Framework\View\Element\Template;
use Magento\Theme\Block\Html\Pager;
use OEscobar\Blog\Model\ResourceModel\Post\Collection as PostCollection;
use OEscobar\Blog\Model\ResourceModel\Post\CollectionFactory as PostCollectionFactory;

class ListPosts extends Template
{
    /**
     * @var PostCollection|null
     */
    private ?PostCollection $collection = null;

    /**
     * @param Template\Context $context
     * @param PostCollectionFactory $postCollectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        private readonly PostCollectionFactory $postCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * @return PostCollection
     */
    public function getCollection(): PostCollection
    {
        if ($this->collection !== null) {
            return $this->collection;
        }

        $req   = $this->getRequest();
        $q      = trim((string)$req->getParam('q', ''));
        $author = trim((string)$req->getParam('author', ''));

        $page  = max(1, (int)$req->getParam('p', 1));
        $limit = max(1, min(24, (int)$req->getParam('limit', 9)));

        $c = $this->postCollectionFactory->create();
        $c->addFieldToSelect('*');

        $c->addFieldToFilter('status', 1);

        if ($author !== '') {
            $c->addFieldToFilter('author', ['like' => "%{$author}%"]);
        }
        if ($q !== '') {
            $c->addFieldToFilter(
                ['title', 'content'],
                [
                    ['like' => "%{$q}%"],
                    ['like' => "%{$q}%"],
                ]
            );
        }

        $c->setOrder('created_at', 'DESC');
        $c->setPageSize($limit)->setCurPage($page);

        $this->collection = $c;
        return $this->collection;
    }

    /**
     * @return $this|ListPosts
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $this->pageConfig->getTitle()->set(__('Blog'));
        $this->pageConfig->setDescription(__('Latest posts on the blog'));

        $collection = $this->getCollection();

        /** @var Pager $pager */
        $pager = $this->getLayout()->createBlock(Pager::class, 'oescobar.blog.pager');
        $pager->setAvailableLimit([6 => 6, 9 => 9, 12 => 12, 24 => 24]);
        $pager->setShowPerPage(true);
        $pager->setCollection($collection);

        $this->setChild('pager', $pager);

        return $this;
    }

    /**
     * @return string
     */
    public function getPagerHtml(): string
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @return string[]
     */
    public function getFilters(): array
    {
        $r = $this->getRequest();
        return [
            'q'      => (string)$r->getParam('q', ''),
            'author' => (string)$r->getParam('author', ''),
            'limit'  => (string)$r->getParam('limit', '9'),
        ];
    }

    /**
     * @return string
     */
    public function getFormAction(): string
    {
        return $this->getUrl('blog/post/index');
    }

    /**
     * @param int $id
     * @return string
     */
    public function getPostUrl(int $id): string
    {
        return $this->getUrl('blog/post/view', ['id' => $id]);
    }
}
