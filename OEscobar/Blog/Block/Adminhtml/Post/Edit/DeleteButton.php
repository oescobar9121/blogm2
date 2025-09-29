<?php

namespace OEscobar\Blog\Block\Adminhtml\Post\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        $id = $this->getIdParam();
        if (!$id) {
            return [];
        }

        $deleteUrl = $this->getUrl('oescobar_blog/post/delete', ['id' => $id]);

        return [
            'label' => __('Delete'),
            'class' => 'delete',
            'on_click' => "deleteConfirm('" . __('Are you sure you want to do this?') . "', '{$deleteUrl}')",
            'data_attribute' => [
                'form-role' => 'delete',
            ],
            'sort_order' => 40
        ];
    }
}
