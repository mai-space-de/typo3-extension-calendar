<?php

declare(strict_types=1);

defined('TYPO3') or die();

// Register the "Calendar View" content element
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    [
        'label' => 'LLL:EXT:mai_calendar/Resources/Private/Language/locallang_db.xlf:tt_content.CType.mai_calendar_view',
        'value' => 'mai_calendar_view',
        'icon' => 'EXT:mai_calendar/Resources/Public/Icons/ContentElement/CalendarView.svg',
        'group' => 'default',
    ],
    'CType',
    'mai_calendar'
);

// Assign FlexForm to the content element
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:mai_calendar/Configuration/FlexForms/Calendar.xml',
    'mai_calendar_view'
);

// Show FlexForm field and hide unused standard fields
$GLOBALS['TCA']['tt_content']['types']['mai_calendar_view'] = [
    'showitem' => '
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general,
            --palette--;;general,
            header,
            pi_flexform,
        --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
            --palette--;;hidden,
            --palette--;;access,
    ',
    'columnsOverrides' => [
        'pi_flexform' => [
            'label' => 'LLL:EXT:mai_calendar/Resources/Private/Language/locallang_db.xlf:tt_content.pi_flexform.mai_calendar_view',
        ],
    ],
];
