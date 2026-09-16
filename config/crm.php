<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BNS CRM Portal Access
    |--------------------------------------------------------------------------
    | Used by https://…/crm — session attendance CRM, separate from reporting.
    */
    'username' => env('CRM_PORTAL_USER', 'bnscrm'),
    'password' => env('CRM_PORTAL_PASSWORD', 'BnsCrm@2026'),
    'session_key' => 'bns_crm_portal_auth',

    'page' => [
        'title' => 'BNS CRM',
        'subtitle' => 'Introduction session attendance',
        'label' => 'CRM Portal',
        'intro' => 'View every introduction session, assign members to employees, and manage calls with 3 follow-ups.',
    ],

    'followup_count' => 3,
];
