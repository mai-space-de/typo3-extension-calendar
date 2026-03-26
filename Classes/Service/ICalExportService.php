<?php

declare(strict_types = 1);

namespace Maispace\MaiCalendar\Service;

use Maispace\MaiCalendar\Domain\Model\Event;

/**
 * Generates iCalendar (RFC 5545) output from a list of events.
 */
class ICalExportService
{
    private const ICAL_EOL = "\r\n";
    private const LINE_LENGTH = 75;

    /**
     * Generates a complete iCalendar string for the given events.
     *
     * @param Event[] $events
     */
    public function generate(array $events, string $calendarName = 'Calendar'): string
    {
        $lines = [];
        $lines[] = 'BEGIN:VCALENDAR';
        $lines[] = 'VERSION:2.0';
        $lines[] = 'PRODID:-//Maispace//MaiCalendar//EN';
        $lines[] = 'CALSCALE:GREGORIAN';
        $lines[] = 'METHOD:PUBLISH';
        $lines[] = $this->fold('X-WR-CALNAME:' . $this->escapeText($calendarName));
        $lines[] = 'X-WR-TIMEZONE:UTC';

        foreach ($events as $event) {
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = $this->fold('UID:' . $this->generateEventUid($event));
            $lines[] = 'DTSTAMP:' . gmdate('Ymd\THis\Z');

            if ($event->isAllDay()) {
                $lines[] = 'DTSTART;VALUE=DATE:' . $event->getStart()->format('Ymd');
                $lines[] = 'DTEND;VALUE=DATE:' . $event->getEnd()->format('Ymd');
            } else {
                $lines[] = 'DTSTART:' . $this->formatDateTime($event->getStart());
                $lines[] = 'DTEND:' . $this->formatDateTime($event->getEnd());
            }

            $lines[] = $this->fold('SUMMARY:' . $this->escapeText($event->getTitle()));

            if ($event->getDescription() !== '') {
                $lines[] = $this->fold('DESCRIPTION:' . $this->escapeText($event->getDescription()));
            }

            if ($event->getLocation() !== '') {
                $lines[] = $this->fold('LOCATION:' . $this->escapeText($event->getLocation()));
            }

            if ($event->getUrl() !== '') {
                $lines[] = $this->fold('URL:' . $event->getUrl());
            }

            $lines[] = 'END:VEVENT';
        }

        $lines[] = 'END:VCALENDAR';

        return implode(self::ICAL_EOL, $lines) . self::ICAL_EOL;
    }

    /**
     * Escapes special characters in iCalendar text values (RFC 5545 §3.3.11).
     */
    private function escapeText(string $text): string
    {
        $text = str_replace('\\', '\\\\', $text);
        $text = str_replace(';', '\\;', $text);
        $text = str_replace(',', '\\,', $text);
        $text = str_replace("\r\n", '\\n', $text);
        $text = str_replace("\n", '\\n', $text);
        $text = str_replace("\r", '\\n', $text);

        return $text;
    }

    /**
     * Folds long content lines to a maximum of 75 octets (RFC 5545 §3.1).
     */
    private function fold(string $line): string
    {
        if (mb_strlen($line, 'UTF-8') <= self::LINE_LENGTH) {
            return $line;
        }

        $folded = '';
        $currentLength = 0;

        foreach (mb_str_split($line, 1, 'UTF-8') as $char) {
            $charBytes = strlen($char);
            if ($currentLength > 0 && $currentLength + $charBytes > self::LINE_LENGTH) {
                $folded .= self::ICAL_EOL . ' ';
                $currentLength = 1; // space counts as 1
            }
            $folded .= $char;
            $currentLength += $charBytes;
        }

        return $folded;
    }

    private function formatDateTime(\DateTimeInterface $dt): string
    {
        $utc = \DateTimeImmutable::createFromInterface($dt)->setTimezone(new \DateTimeZone('UTC'));

        return $utc->format('Ymd\THis\Z');
    }

    private function generateEventUid(Event $event): string
    {
        return $event->getUid() . '@mai.space';
    }
}
