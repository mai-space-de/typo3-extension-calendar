<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Mai Calendar',
    'description' => 'A calendar extension with month, week, and list views and iCal export. Aggregates events from multiple sources via `EventProviderInterface`. Extensions that provide events (e.g. `mai_project`) implement this interface and declare `maispace/mai-calendar` as a suggested dependency.',
    'category' => 'module',
    'author' => 'Maispace',
    'author_email' => '',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
