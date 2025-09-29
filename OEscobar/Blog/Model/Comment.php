<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use OEscobar\Blog\Api\Data\CommentInterface;

class Comment extends AbstractModel implements CommentInterface
{
    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _construct(): void
    {
        $this->_init(\OEscobar\Blog\Model\ResourceModel\Comment::class);
    }

    /**
     * @return int
     */
    public function getPostId(): int
    {
        return (int)$this->getData(self::POST_ID);
    }

    /**
     * @param int $postId
     * @return CommentInterface
     */
    public function setPostId(int $postId): CommentInterface
    {
        return $this->setData(self::POST_ID, $postId);
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return (string)$this->getData(self::CONTENT);
    }

    /**
     * @param string $content
     * @return CommentInterface
     */
    public function setContent(string $content): CommentInterface
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @return string|null
     */
    public function getAuthor(): ?string
    {
        $v = $this->getData(self::AUTHOR);
        return $v !== null ? (string)$v : null;
    }

    /**
     * @param string|null $author
     * @return CommentInterface
     */
    public function setAuthor(?string $author): CommentInterface
    {
        return $this->setData(self::AUTHOR, $author);
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return (int)$this->getData(self::STATUS);
    }

    /**
     * @param int $status
     * @return CommentInterface
     */
    public function setStatus(int $status): CommentInterface
    {
        return $this->setData(self::STATUS, $status);
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return (string)$this->getData(self::CREATED_AT);
    }
}
