<?php
declare(strict_types=1);

namespace OEscobar\Blog\Controller\Comment;

use DateTimeImmutable;
use DateTimeZone;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Message\ManagerInterface;
use OEscobar\Blog\Model\CommentFactory;
use OEscobar\Blog\Model\ResourceModel\Comment as CommentResource;

class Submit implements HttpPostActionInterface
{
    /**
     * @param RequestInterface $request
     * @param RedirectFactory $redirectFactory
     * @param ManagerInterface $messageManager
     * @param Validator $formKeyValidator
     * @param CommentFactory $commentFactory
     * @param CommentResource $commentResource
     */
    public function __construct(
        private readonly RequestInterface $request,
        private readonly RedirectFactory  $redirectFactory,
        private readonly ManagerInterface $messageManager,
        private readonly Validator        $formKeyValidator,
        private readonly CommentFactory   $commentFactory,
        private readonly CommentResource  $commentResource
    )
    {
    }

    /**
     * @return ResultInterface
     * @throws \DateMalformedStringException
     */
    public function execute(): ResultInterface
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->redirectFactory->create();

        $postId = (int)($this->request->getParam('post_id') ?? 0);
        if ($postId <= 0) {
            $this->messageManager->addErrorMessage(__('Invalid post.'));
            return $resultRedirect->setPath('blog');
        }

        if (!$this->formKeyValidator->validate($this->request)) {
            $this->messageManager->addErrorMessage(__('Invalid form key.'));
            return $resultRedirect->setPath('blog/post/view', ['id' => $postId]);
        }

        $author = trim((string)$this->request->getParam('author'));
        $content = trim((string)$this->request->getParam('content'));
        if ($author === '' || $content === '') {
            $this->messageManager->addErrorMessage(__('Please fill all required fields.'));
            return $resultRedirect->setPath('blog/post/view', ['id' => $postId]);
        }

        $now = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');

        $comment = $this->commentFactory->create();
        $comment->addData([
            'post_id' => $postId,
            'author' => $author,
            'content' => $content,
            'status' => 0,
            'created_at' => $now,
        ]);

        try {
            $this->commentResource->save($comment);
            $this->messageManager->addSuccessMessage(__('Comment submitted.'));
        } catch (\Throwable $e) {
            $this->messageManager->addErrorMessage(__('Could not save comment.'));
        }

        return $resultRedirect->setPath('blog/post/view', ['id' => $postId]);
    }
}
