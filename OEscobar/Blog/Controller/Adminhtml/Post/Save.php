<?php
declare(strict_types=1);

namespace OEscobar\Blog\Controller\Adminhtml\Post;

use DateTimeImmutable;
use DateTimeZone;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\Result\Redirect as ResultRedirect;
use OEscobar\Blog\Model\PostFactory;
use OEscobar\Blog\Model\ResourceModel\Post as PostResource;
use Throwable;

class Save extends Action
{
    /**
     *
     */
    public const ADMIN_RESOURCE = 'OEscobar_Blog::posts';

    /**
     * @param Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param PostFactory $postFactory
     * @param PostResource $postResource
     */
    public function __construct(
        Context                                 $context,
        private readonly DataPersistorInterface $dataPersistor,
        private readonly PostFactory            $postFactory,
        private readonly PostResource           $postResource
    )
    {
        parent::__construct($context);
    }

    /**
     * @return ResultRedirect
     */
    public function execute(): ResultRedirect
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $post = (array)($this->getRequest()->getPostValue() ?? []);
        if ($post === []) {
            return $resultRedirect->setPath('*/*/');
        }

        $root = (array)($post['data'] ?? $post);
        $payload = (array)($root['post'] ?? $root);

        $idFromQuery = (int)($this->getRequest()->getParam('id') ?? 0);
        $idFromForm = isset($payload['post_id']) ? (int)$payload['post_id'] : 0;
        $id = $idFromQuery > 0 ? $idFromQuery : $idFromForm;

        $model = $this->postFactory->create();

        try {
            if ($id > 0) {
                $this->postResource->load($model, $id);
                if (!$model->getId()) {
                    $this->messageManager->addErrorMessage(__('This post no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
                $model->setId($id);
            }

            $dataToSave = [
                'title' => $payload['title'] ?? '',
                'content' => $payload['content'] ?? '',
                'author' => $payload['author'] ?? '',
                'status' => isset($payload['status']) ? (int)$payload['status'] : 0,
            ];

            $now = $this->nowUtc();
            if ($id === 0) { // nuevo
                $dataToSave['created_at'] = $now;
            }
            $dataToSave['updated_at'] = $now;

            // ⚠️ IMPORTANTE: addData para no perder el post_id cargado
            $model->addData(array_filter($dataToSave, static fn($v) => $v !== null));

            $this->postResource->save($model);

            $this->messageManager->addSuccessMessage(__('You saved the post.'));
            $this->dataPersistor->clear('oescobar_blog_post');

            if ($this->getRequest()->getParam('back')) {
                return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
            }
            return $resultRedirect->setPath('*/*/');

        } catch (Throwable $e) {
            $this->messageManager->addErrorMessage(__('Could not save: %1', $e->getMessage()));
            $this->dataPersistor->set('oescobar_blog_post', $payload);
            return $resultRedirect->setPath('*/*/edit', ['id' => $id ?: null]);
        }
    }

    /**
     * @return string
     * @throws \DateMalformedStringException
     */
    private function nowUtc(): string
    {
        return (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
    }
}
