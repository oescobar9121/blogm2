<?php
declare(strict_types=1);

namespace OEscobar\Blog\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use OEscobar\Blog\Model\PostFactory;

use OEscobar\Blog\Api\PostRepositoryInterface;

class InstallSamplePost implements DataPatchInterface
{
    /**
     * @param PostFactory $postFactory
     * @param PostRepositoryInterface $postRepository
     */
    public function __construct(
        private readonly PostFactory             $postFactory,
        private readonly PostRepositoryInterface $postRepository
    )
    {
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function apply()
    {
        $post = $this->postFactory->create();
        $post->setTitle('Hello World')
            ->setContent('This is your first blog post')
            ->setAuthor('System')
            ->setStatus(1);

        $this->postRepository->save($post);
    }

    /**
     * @return array|string[]
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * @return array|string[]
     */
    public function getAliases(): array
    {
        return [];
    }
}
