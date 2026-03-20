<?php

declare(strict_types=1);

namespace Maispace\MaiCalendar\EventProvider;

use Maispace\MaiCalendar\Domain\Model\Event;

/**
 * Interface for event providers.
 *
 * Implement this interface to supply events to the CalendarDataProcessor
 * from any data source (e.g. maispace/project, external APIs, database records).
 *
 * Implementations must be tagged with the service tag
 * `maispace.calendar.event_provider` in Configuration/Services.yaml
 * so that they are automatically discovered and registered.
 */
interface EventProviderInterface
{
    /**
     * Returns all events within the given date range.
     *
     * @param \DateTimeInterface $start Inclusive start of the range
     * @param \DateTimeInterface $end   Inclusive end of the range
     * @return Event[]
     */
    public function getEvents(\DateTimeInterface $start, \DateTimeInterface $end): array;
}
