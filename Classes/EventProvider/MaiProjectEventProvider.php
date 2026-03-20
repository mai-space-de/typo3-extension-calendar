<?php

declare(strict_types=1);

namespace Maispace\MaiCalendar\EventProvider;

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
                    $queryBuilder->createNamedParameter($end->getTimestamp(), \PDO::PARAM_INT)
                ),
                $queryBuilder->expr()->gte(
                    'event_end',
                    $queryBuilder->createNamedParameter($start->getTimestamp(), \PDO::PARAM_INT)
                )
            )
            ->executeQuery()
            ->fetchAllAssociative();

        $events = [];
        foreach ($rows as $row) {
            $events[] = new Event(
                uid: 'maiproject_' . $row['uid'],
                title: $row['title'],
                start: new \DateTimeImmutable('@' . $row['event_start']),
                end: new \DateTimeImmutable('@' . $row['event_end']),
                description: $row['description'] ?? '',
                location: $row['location'] ?? '',
                url: $row['slug'] ?? '',
                source: 'maiproject',
            );
        }

        return $events;
    }
}
