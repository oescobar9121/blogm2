<?php
declare(strict_types=1);

namespace OEscobar\Blog\Api\Data;

interface PostInterface
{
    public const POST_ID = 'post_id';
    public const TITLE = 'title';
    public const CONTENT = 'content';
    public const AUTHOR = 'author';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    /** @return string */
    public function getTitle(): string;

    /**
     * @param string $title
     * @return PostInterface
     */
    public function setTitle(string $title): self;

    /** @return string */
    public function getContent(): string;

    /**
     * @param string $content
     * @return PostInterface
     */
    public function setContent(string $content): self;

    /** @return string|null */
    public function getAuthor(): ?string;

    /**
     * @param string|null $author
     * @return PostInterface
     */
    public function setAuthor(?string $author): self;

    /** @return int */
    public function getStatus(): int;

    /**
     * @param int $status
     * @return PostInterface
     */
    public function setStatus(int $status): self;

    /** @return string */
    public function getCreatedAt(): string;

    /** @return string */
    public function getUpdatedAt(): string;
}
