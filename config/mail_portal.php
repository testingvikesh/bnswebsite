<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BNS Mail Portal Access
    |--------------------------------------------------------------------------
    | Used by https://…/mail — separate from controlpanel admin login.
    */
    'username' => env('MAIL_PORTAL_USER', 'bnsmail'),
    'password' => env('MAIL_PORTAL_PASSWORD', 'BnsMail@2026'),
    'session_key' => 'bns_mail_portal_auth',

    'hub' => [
        'title' => 'BNS Mail',
        'subtitle' => 'Choose your mail workspace',
        'label' => 'Mail Portal',
        'intro' => 'Sign in once, then open Student Mail (Stage 1–10 sequence) or Business Coach Mail (flat message box set).',
    ],

    'pages' => [
        'student' => [
            'title' => 'BNS Student Mail',
            'audience' => 'student',
            'badge' => 'Student',
            'icon' => 'fas fa-user-graduate',
        ],
        'business_coach' => [
            'title' => 'BNS Business Coach Mail',
            'audience' => 'business_coach',
            'badge' => 'Business Coach',
            'icon' => 'fas fa-chalkboard-teacher',
        ],
    ],
];
