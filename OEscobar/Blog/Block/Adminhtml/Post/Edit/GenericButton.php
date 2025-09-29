<?php

namespace OEscobar\Blog\Block\Adminhtml\Post\Edit;

use Magento\Backend\Block\Widget\Context;

abstract class GenericButton
{
    /**
     * @param Context $context
     */
    public function __construct(protected Context $context)
    {
    }

    /**
     * @return int|null
     */
    protected function getIdParam(): ?int
    {
        $id = (int)$this->context->getRequest()->getParam('id');
        return $id ?: null;
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    protected function getUrl(string $route = '', array $params = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}
