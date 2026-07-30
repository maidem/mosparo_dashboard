<?php

$EM_CONF['mosparo_dashboard'] = [
    'title' => 'Maidem Mosparo Dashboard',
    'description' => 'TYPO3 dashboard widgets showing mosparo spam-protection statistics (valid/spam submissions).',
    'category' => 'be',
    'author' => 'Maik Demuth',
    'author_email' => '',
    'state' => 'beta',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'php' => '8.4.0-0.0.0',
            'typo3' => '14.1.0-14.99.99',
            'dashboard' => '14.1.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
