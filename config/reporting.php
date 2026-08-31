<?php

return [

    'form_sources' => [
        'intro-session-modal' => 'Introduction Session',
        'inquiry-modal' => 'General Inquiry',
        'register-quick-modal' => 'Confirm Admission',
        'contact-page' => 'Contact Page',
        'unknown' => 'Unknown / Legacy',
    ],

    'statuses' => [
        'pending' => 'Pending',
        'reviewed' => 'Reviewed',
        'contacted' => 'Contacted',
        'closed' => 'Closed',
    ],

    'refund_otp' => [
        'email' => env('REPORTING_REFUND_OTP_EMAIL', 'mrupani2005@gmail.com'),
        'ttl_minutes' => (int) env('REPORTING_REFUND_OTP_TTL', 10),
        'length' => 6,
    ],

];
