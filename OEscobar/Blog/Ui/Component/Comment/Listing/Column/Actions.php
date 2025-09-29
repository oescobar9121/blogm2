<?php
declare(strict_types=1);

namespace OEscobar\Blog\Ui\Component\Comment\Listing\Column;

use Magento\Framework\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;

class Actions extends Column
{
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        private readonly UrlInterface $urlBuilder,
        private readonly RequestInterface $request,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource): array
    {
        if (!isset($dataSource['data']['items'])) {
            return $dataSource;
        }

        $name = (string)$this->getData('name');

        $approveUrl   = (string)($this->getData('approveUrl')   ?? 'oescobar_blog/comment/approve');
        $unapproveUrl = (string)($this->getData('unapproveUrl') ?? 'oescobar_blog/comment/unapprove');
        $deleteUrl    = (string)($this->getData('deleteUrl')    ?? 'oescobar_blog/comment/delete');

        $backId = (int)($this->request->getParam('id') ?? 0); // para volver al view del post

        foreach ($dataSource['data']['items'] as &$item) {
            if (!isset($item['comment_id'])) {
                continue;
            }
            $id     = (int)$item['comment_id'];
            $status = (int)($item['status'] ?? 0);

            if ($status === 1) {
                $item[$name]['unapprove'] = [
                    'href'  => $this->urlBuilder->getUrl($unapproveUrl, ['id' => $id, 'back_id' => $backId]),
                    'label' => __('Unapprove'),
                ];
            } else {
                $item[$name]['approve'] = [
                    'href'  => $this->urlBuilder->getUrl($approveUrl, ['id' => $id, 'back_id' => $backId]),
                    'label' => __('Approve'),
                ];
            }

            $item[$name]['delete'] = [
                'href'    => $this->urlBuilder->getUrl($deleteUrl, ['id' => $id, 'back_id' => $backId]),
                'label'   => __('Delete'),
                'confirm' => [
                    'title'   => __('Delete Comment'),
                    'message' => __('Are you sure you want to delete this comment?'),
                ],
            ];
        }

        return $dataSource;
    }
}
