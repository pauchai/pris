<?php
return [
    'test' => [
        'type' => 2,
    ],
    'editPerson' => [
        'type' => 2,
    ],
    'editOwnPerson' => [
        'type' => 2,
        'ruleName' => 'own',
        'children' => [
            'editPerson',
        ],
    ],
];
