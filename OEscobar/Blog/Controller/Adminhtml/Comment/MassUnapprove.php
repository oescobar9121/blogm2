<?php
declare(strict_types=1);

namespace OEscobar\Blog\Controller\Adminhtml\Comment;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use OEscobar\Blog\Model\ResourceModel\Comment as CommentResource;

class MassUnapprove extends Action
{
    public const ADMIN_RESOURCE = 'OEscobar_Blog::posts';

    public function __construct(
        Context                 $context,
        private CommentResource $commentResource
    )
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $ids = array_map('intval', (array)($this->getRequest()->getParam('selected') ?? []));
        $backId = (int)($this->getRequest()->getParam('back_id') ?? 0);

        if (!$ids) {
            $this->messageManager->addErrorMessage(__('No comments selected.'));
            return $this->resultRedirectFactory->create()->setPath('oescobar_blog/post/index');
        }

        try {
            $conn = $this->commentResource->getConnection();
            $table = $this->commentResource->getMainTable();

            $conn->update($table, ['status' => 0], ['comment_id IN (?)' => $ids]);

            $this->messageManager->addSuccessMessage(__('Unapproved %1 comment(s).', count($ids)));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Could not unapprove: %1', $e->getMessage()));
        }

        if ($backId > 0) {
            return $this->resultRedirectFactory->create()->setPath('oescobar_blog/post/view', ['id' => $backId]);
        }
        return $this->resultRedirectFactory->create()->setPath('oescobar_blog/post/index');
    }
}
