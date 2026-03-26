<?php

declare(strict_types = 1);

namespace Maispace\MaiCalendar\Controller;

use Maispace\MaiCalendar\Domain\Model\Event;
use Maispace\MaiCalendar\EventProvider\EventProviderInterface;
use Maispace\MaiCalendar\Service\ICalExportService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Controller providing the iCal export endpoint.
 */
class CalendarController extends ActionController
{
    /**
     * @param iterable<EventProviderInterface> $eventProviders
     */
    public function __construct(
        private readonly iterable $eventProviders,
        private readonly ICalExportService $iCalExportService,
    ) {
    }

    /**
     * Exports all events within the requested range as an iCalendar (.ics) file.
     *
     * Query parameters:
     *   start  Y-m-d   (default: first day of current month)
     *   end    Y-m-d   (default: last day of current month)
     */
    public function icalExportAction(): ResponseInterface
    {
        $startArg = $this->request->hasArgument('start') ? $this->request->getArgument('start') : null;
        $start = $this->resolveDate(
            is_string($startArg) ? $startArg : '',
            new \DateTimeImmutable('first day of this month')
        );
        $endArg = $this->request->hasArgument('end') ? $this->request->getArgument('end') : null;
        $end = $this->resolveDate(
            is_string($endArg) ? $endArg : '',
            new \DateTimeImmutable('last day of this month midnight')
        );

        $events = $this->aggregateEvents($start, $end);
        $icalContent = $this->iCalExportService->generate($events);

        $response = new Response();
        $response->getBody()->write($icalContent);

        return $response
            ->withHeader('Content-Type', 'text/calendar; charset=utf-8')
            ->withHeader('Content-Disposition', 'attachment; filename="calendar.ics"');
    }

    private function resolveDate(string $value, \DateTimeImmutable $default): \DateTimeImmutable
    {
        if ($value === '') {
            return $default->setTime(0, 0, 0);
        }

        $parsed = \DateTimeImmutable::createFromFormat('Y-m-d', $value);
        if ($parsed === false) {
            return $default->setTime(0, 0, 0);
        }

        return $parsed->setTime(0, 0, 0);
    }

    /**
     * @return Event[]
     */
    private function aggregateEvents(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        $events = [];
        foreach ($this->eventProviders as $provider) {
            foreach ($provider->getEvents($start, $end) as $event) {
                $events[] = $event;
            }
        }

        usort($events, static fn (Event $a, Event $b) => $a->getStart() <=> $b->getStart());

        return $events;
    }
}
