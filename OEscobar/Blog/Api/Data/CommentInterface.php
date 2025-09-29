<?php
declare(strict_types=1);

namespace OEscobar\Blog\Api\Data;

interface CommentInterface
{
    public const COMMENT_ID = 'comment_id';
    public const POST_ID = 'post_id';
    public const CONTENT = 'content';
    public const AUTHOR = 'author';
    public const STATUS = 'status';
    public const CREATED_AT = 'created_at';

    /** @return int */
    public function getPostId(): int;

    /**
     * @param int $postId
     * @return CommentInterface
     */
    public function setPostId(int $postId): self;

    /** @return string */
    public function getContent(): string;

    /**
     * @param string $content
     * @return CommentInterface
     */
    public function setContent(string $content): self;

    /** @return string|null */
    public function getAuthor(): ?string;

    /**
     * @param string|null $author
     * @return CommentInterface
     */
    public function setAuthor(?string $author): self;

    /** @return int */
    public function getStatus(): int;

    /**
     * @param int $status
     * @return CommentInterface
     */
    public function setStatus(int $status): self;

    /** @return string */
    public function getCreatedAt(): string;
}
