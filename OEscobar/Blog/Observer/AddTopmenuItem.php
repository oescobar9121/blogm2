<?php
declare(strict_types=1);

namespace OEscobar\Blog\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Data\Tree\NodeFactory;

class AddTopmenuItem implements ObserverInterface
{
    /**
     * @param NodeFactory $nodeFactory
     */
    public function __construct(private readonly NodeFactory $nodeFactory)
    {
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer): void
    {
        $menu = $observer->getData('menu');
        $tree = $menu->getTree();

        $node = $this->nodeFactory->create([
            'data' => [
                'name' => __('Blog'),
                'id' => 'oescobar-blog-topmenu',
                'url' => $observer->getData('block')->getUrl('blog'),
                'is_active' => false,
            ],
            'idField' => 'id',
            'tree' => $tree
        ]);

        $menu->addChild($node);
    }
}
