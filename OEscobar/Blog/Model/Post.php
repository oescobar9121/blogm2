<?php
declare(strict_types=1);

namespace OEscobar\Blog\Model;

use Magento\Framework\Model\AbstractModel;
use OEscobar\Blog\Api\Data\PostInterface;

class Post extends AbstractModel implements PostInterface
{
    /**
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _construct(): void
    {
        $this->_init(\OEscobar\Blog\Model\ResourceModel\Post::class);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return (string)$this->getData(self::TITLE);
    }

    /**
     * @param string $title
     * @return PostInterface
     */
    public function setTitle(string $title): PostInterface
    {
        return $this->setData(self::TITLE, $title);
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
     * @return PostInterface
     */
    public function setContent(string $content): PostInterface
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
     * @return PostInterface
     */
    public function setAuthor(?string $author): PostInterface
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
     * @return PostInterface
     */
    public function setStatus(int $status): PostInterface
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

    /**
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return (string)$this->getData(self::UPDATED_AT);
    }
}
