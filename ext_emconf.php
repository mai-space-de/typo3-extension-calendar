<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Mai Calendar',
    'description' => 'Calendar view extension with month, week and list views and iCal export. Aggregates events from all EventProviderInterface implementations.',
    'category' => 'plugin',
    'author' => 'Mai Space',
    'author_email' => 'info@mai.space',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.0.0-13.99.99',
            'frontend' => '12.0.0-13.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
