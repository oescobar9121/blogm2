<?php
declare(strict_types=1);

namespace OEscobar\Blog\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

class PostActions extends Column
{
    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface     $context,
        UiComponentFactory   $uiComponentFactory,
        private UrlInterface $urlBuilder,
        array                $components = [],
        array                $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $indexField = (string)($this->getData('config/indexField') ?: 'post_id');
        $name = (string)$this->getData('name'); // "actions"

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item[$indexField])) {
                continue;
            }
            $id = $item[$indexField];

            $item[$name]['edit'] = [
                'href' => $this->urlBuilder->getUrl('oescobar_blog/post/edit', ['post_id' => $id]),
                'label' => __('Edit'),
            ];
            $item[$name]['delete'] = [
                'href' => $this->urlBuilder->getUrl('oescobar_blog/post/delete', ['post_id' => $id]),
                'label' => __('Delete'),
                'confirm' => [
                    'title' => __('Delete'),
                    'message' => __('Are you sure you want to delete this post?'),
                ],
                'post' => true,
            ];
        }

        return $dataSource;
    }
}
