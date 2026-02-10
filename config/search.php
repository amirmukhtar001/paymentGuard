<?php

return [

    'modules' => [

        'pages' => [
            'model' => App\Models\Page::class,
            'columns' => ['title', 'body'],
            'select' => [ 'title', 'slug'],
            'conditions' => [
                'status' => 'published'
            ],
        ],

        'cabinetmembers' => [
            'model' => App\Models\Web\CabinetMember::class,
            'columns' => ['name', 'message'],
            'select' => [ 'name', 'name'],
        ],

        'acts' => [
            'model' => App\Models\Web\Code::class,
            'columns' => ['title', 'description'],
            'select' => ['title', 'slug'],
        ],
        'employees' => [
            'model' => App\Models\Web\Employee::class,
            'columns' => ['name', 'message'],
            'select' => [ 'name', 'name'],
        ],

        'events' => [
            'model' => App\Models\Event::class,
            'columns' => ['title', 'body'],
            'select' => ['title', 'slug'],
        ],

    ],

];
