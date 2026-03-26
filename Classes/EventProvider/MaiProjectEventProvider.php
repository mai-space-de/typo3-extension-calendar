<?php

declare(strict_types = 1);

namespace Maispace\MaiCalendar\EventProvider;

use Doctrine\DBAL\ParameterType;
use Maispace\MaiCalendar\Domain\Model\Event;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Example event provider that reads events from the maispace/project extension.
 *
 * This class serves as a reference implementation and demonstrates how to
 * connect an external data source to the calendar. Register additional
 * providers by implementing EventProviderInterface and tagging the service
 * with `maispace.calendar.event_provider` in Configuration/Services.yaml.
 */
class MaiProjectEventProvider implements EventProviderInterface
{
    public function getEvents(\DateTimeInterface $start, \DateTimeInterface $end): array
    {
        /** @var ConnectionPool $connectionPool */
        $connectionPool = GeneralUtility::makeInstance(ConnectionPool::class);
        $queryBuilder = $connectionPool->getQueryBuilderForTable('tx_maiproject_domain_model_project');

        $rows = $queryBuilder
            ->select('uid', 'title', 'description', 'event_start', 'event_end', 'location', 'slug')
            ->from('tx_maiproject_domain_model_project')
            ->where(
                $queryBuilder->expr()->isNotNull('event_start'),
                $queryBuilder->expr()->lte(
                    'event_start',
                    $queryBuilder->createNamedParameter($end->getTimestamp(), ParameterType::INTEGER)
                ),
                $queryBuilder->expr()->gte(
                    'event_end',
                    $queryBuilder->createNamedParameter($start->getTimestamp(), ParameterType::INTEGER)
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();

        $events = [];
        foreach ($rows as $row) {
            $uid = is_int($row['uid'] ?? null) ? $row['uid'] : 0;
            $title = is_string($row['title'] ?? null) ? $row['title'] : '';
            $eventStart = is_int($row['event_start'] ?? null) ? $row['event_start'] : 0;
            $eventEnd = is_int($row['event_end'] ?? null) ? $row['event_end'] : 0;
            $description = is_string($row['description'] ?? null) ? $row['description'] : '';
            $location = is_string($row['location'] ?? null) ? $row['location'] : '';
            $slug = is_string($row['slug'] ?? null) ? $row['slug'] : '';

            $events[] = new Event(
                uid: 'maiproject_' . $uid,
                title: $title,
                start: new \DateTimeImmutable('@' . $eventStart),
                end: new \DateTimeImmutable('@' . $eventEnd),
                description: $description,
                location: $location,
                url: $slug,
                source: 'maiproject',
            );
        }

        return $events;
    }
}
