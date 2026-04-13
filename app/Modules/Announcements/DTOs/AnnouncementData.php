<?php

namespace App\Modules\Announcements\DTOs;

use DateTimeInterface;

readonly class AnnouncementData
{
    public function __construct(
        public string $title,
        public string $content,
        public bool $isPublished,
        public ?DateTimeInterface $publishedAt,
        public ?DateTimeInterface $expiresAt,
    ) {
    }

    public static function fromArray(
        array $data,
        bool $isPublished,
        ?DateTimeInterface $publishedAt = null,
        ?DateTimeInterface $expiresAt = null,
    ): self {
        return new self(
            title: trim((string) $data['title']),
            content: trim((string) $data['content']),
            isPublished: $isPublished,
            publishedAt: $publishedAt,
            expiresAt: $expiresAt,
        );
    }

    public function toArray(int $userId, ?DateTimeInterface $fallbackPublishedAt = null): array
    {
        return [
            'user_id' => $userId,
            'title' => $this->title,
            'content' => $this->content,
            'is_published' => $this->isPublished,
            'published_at' => $this->publishedAt ?: ($this->isPublished ? $fallbackPublishedAt : null),
            'expires_at' => $this->expiresAt,
        ];
    }
}
