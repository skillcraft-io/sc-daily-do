<?php

return [
    [
        'name' => 'Daily dos',
        'flag' => 'daily-do.index',
    ],
    [
        'name' => 'Create',
        'flag' => 'daily-do.create',
        'parent_flag' => 'daily-do.index',
    ],
    [
        'name' => 'Edit',
        'flag' => 'daily-do.edit',
        'parent_flag' => 'daily-do.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'daily-do.destroy',
        'parent_flag' => 'daily-do.index',
    ],
];
