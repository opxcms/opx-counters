<?php

return [
    'items' => [
        'counters' => [
            'caption' => 'opx_counters::manage.counters',
            'route' => 'opx_counters::counters_list',
            'section' => 'system/site',
        ],
    ],

    'routes' => [
        'opx_counters::counters_list' => [
            'route' => '/counters',
            'loader' => 'manage/api/module/opx_counters/counters_list',
        ],
        'opx_counters::counters_add' => [
            'route' => '/counters/add',
            'loader' => 'manage/api/module/opx_counters/counters_edit/add',
        ],
        'opx_counters::counters_edit' => [
            'route' => '/counters/edit/:id',
            'loader' => 'manage/api/module/opx_counters/counters_edit/edit',
        ],
    ]
];