<?php
declare(strict_types=1);

namespace OEscobar\Blog\Controller\Post;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class View implements HttpGetActionInterface
{
    /**
     * @param PageFactory $pageFactory
     * @param RedirectFactory $redirectFactory
     * @param RequestInterface $request
     */
    public function __construct(
        private readonly PageFactory      $pageFactory,
        private readonly RedirectFactory  $redirectFactory,
        private readonly RequestInterface $request
    )
    {
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $id = (int)($this->request->getParam('id') ?? 0);
        if ($id <= 0) {
            return $this->redirectFactory->create()->setPath('blog');
        }

        return $this->pageFactory->create();
    }
}
