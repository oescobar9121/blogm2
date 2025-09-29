<?php

namespace OEscobar\Blog\Ui\Component\Post\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;

class Actions extends Column
{
    /** @var UrlInterface */
    protected $urlBuilder;

    /** @var string URL para editar en admin */
    protected $editUrl;

    /** @var string URL para ver en frontend */
    protected $viewUrl;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param string $editUrl
     * @param string $viewUrl
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface   $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface       $urlBuilder,
        string             $editUrl = '',
        string             $viewUrl = '',
        array              $components = [],
        array              $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->editUrl = $editUrl;
        $this->viewUrl = $viewUrl;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['post_id'])) {
                    $postId = $item['post_id'];
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl($this->editUrl, ['id' => $postId]),
                            'label' => __('Edit'),
                            'hidden' => false,
                        ],
                        'view' => [
                            'href' => $this->urlBuilder->getUrl($this->viewUrl, ['id' => $postId]),
                            'label' => __('View'),
                            'hidden' => false,
                            'target' => '_blank'
                        ]
                    ];
                }
            }
        }
        return $dataSource;
    }
}
