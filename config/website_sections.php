<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Available Module Types for Website Sections
    |--------------------------------------------------------------------------
    |
    | This configuration defines the available module types that can be
    | assigned to website sections. Each module type should have:
    | - key: The database value (lowercase, snake_case)
    | - label: Display name for the admin interface
    | - model: Full model class name (optional, for auto-discovery)
    |
    | To add a new module:
    | 1. Add it to the 'modules' array below
    | 2. Add the retrieval method in WebsiteSectionService::getModuleData()
    |
    */

    'modules' => [
        'slider' => [
            'label' => 'Slider',
        ],
        'news' => [
            'label' => 'News',
        ],
        'pages' => [
            'label' => 'Pages',
        ],
        'gallery' => [
            'label' => 'Gallery',
        ],
        'cabinet_members' => [
            'label' => 'Cabinet Members',
        ],
        'social_media' => [
            'label' => 'Social Media',
        ],
        'leaders' => [
            'label' => 'Leader Of Province',
        ],
        'historical_people' => [
            'label' => 'Historical People / Herioes',
        ],
        'services' => [
            'label' => 'Services',
        ],
        'government_transparency' => [
            'label' => 'Government / Transparency',
        ],
        'investment_opportunities' => [
            'label' => 'Investment Opportunities',
        ],
        'all_government_departments' => [
            'label' => 'All Government Departments',
        ],
        'citizen_engagement' => [
            'label' => 'Citizen Engagement',
        ],
        'initiatives' => [
            'label' => 'Initiatives',
        ],
        'map' => [
            'label' => 'Map',
        ],
        'messages' => [
            'label' => 'Home Page Messages Section',
        ],
        'secretary' => [
            'label' => 'Administrative Secretary Section',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Module Data Retrieval Settings
    |--------------------------------------------------------------------------
    |
    | Default settings for retrieving module data
    |
    */

    'default_limit' => 10,
    'default_order' => 'created_at',
    'default_direction' => 'desc',
];
