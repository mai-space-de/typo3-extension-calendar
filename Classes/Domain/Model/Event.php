<?php

declare(strict_types = 1);

namespace Maispace\MaiCalendar\Domain\Model;

/**
 * Represents a single calendar event.
 */
class Event
{
    public function __construct(
        protected string $uid,
        protected string $title,
        protected \DateTimeInterface $start,
        protected \DateTimeInterface $end,
        protected string $description = '',
        protected string $location = '',
        protected string $url = '',
        protected bool $allDay = false,
        protected string $source = '',
    ) {
    }

    public function getUid(): string
    {
        return $this->uid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStart(): \DateTimeInterface
    {
        return $this->start;
    }

    public function getEnd(): \DateTimeInterface
    {
        return $this->end;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getLocation(): string
    {
        return $this->location;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isAllDay(): bool
    {
        return $this->allDay;
    }

    public function getSource(): string
    {
        return $this->source;
    }
}
