<?php

defined('TYPO3') or die();

(static function (): void {
    // Register the iCal export plugin
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'MaiCalendar',
        'ICalExport',
        [
            \Maispace\MaiCalendar\Controller\CalendarController::class => 'icalExport',
        ],
        [
            \Maispace\MaiCalendar\Controller\CalendarController::class => 'icalExport',
        ]
    );

    // Add TypoScript
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptSetup(
        '@import "EXT:mai_calendar/Configuration/TypoScript/setup.typoscript"'
    );
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScriptConstants(
        '@import "EXT:mai_calendar/Configuration/TypoScript/constants.typoscript"'
    );
})();
