<?php

$defaultFee = (string) env('PAYMENT_DEFAULT_AMOUNT', env('PAYMENT_AMOUNT', env('PAYMENT', '100.00')));

return [

    'gateway' => env('PAYMENT_GATEWAY', 'icici'),

    'icici' => [
        'merchant_id' => env('ICICI_MERCHANT_ID', '100000000476854'),
        'aggregator_id' => env('ICICI_AGGREGATOR_ID', '100000000476853'),
        'secret_key' => env('ICICI_SECRET_KEY', ''),
        'currency_code' => env('ICICI_CURRENCY_CODE', '356'),
        'pay_type' => env('ICICI_PAY_TYPE', '0'),
        'initiate_sale_url' => env('ICICI_INITIATE_SALE_URL', 'https://pgpay.icicibank.com/pg/api/v2/initiateSale'),
        'status_check_url' => env('ICICI_STATUS_CHECK_URL', 'https://pgpay.icicibank.com/pg/api/command'),
        'settlement_url' => env('ICICI_SETTLEMENT_URL', 'https://pgpay.icicibank.com/pg/api/settlementDetails'),
        'success_response_codes' => ['0000', '000', '0000/000'],
        'initiate_success_code' => 'R1000',
        'refund_success_codes' => ['0000', '000', '0000/000', 'R1000'],
    ],

    'default_amount' => $defaultFee,

    'registration_fees' => [
        'youth-school' => env('PAYMENT_FEE_YOUTH') ?: $defaultFee,
        'student-school' => env('PAYMENT_FEE_STUDENT') ?: $defaultFee,
        'women-school' => env('PAYMENT_FEE_WOMEN') ?: $defaultFee,
        'working-women-school' => env('PAYMENT_FEE_WORKING_WOMEN') ?: $defaultFee,
        'job-professional-school' => env('PAYMENT_FEE_JOB_PROFESSIONAL') ?: $defaultFee,
        'business-growth-school' => env('PAYMENT_FEE_BUSINESS_GROWTH') ?: $defaultFee,
        'intro-session' => env('PAYMENT_FEE_INTRO_SESSION') ?: $defaultFee,
    ],

    'form_type_map' => [
        'youth-school' => ['model' => \App\Models\YouthAdmission::class, 'label' => 'Youth School'],
        'student-school' => ['model' => \App\Models\StudentAdmission::class, 'label' => 'Student School'],
        'women-school' => ['model' => \App\Models\WomenAdmission::class, 'label' => 'Women Entrepreneurship'],
        'working-women-school' => ['model' => \App\Models\WorkingWomenAdmission::class, 'label' => 'Working Women Leadership'],
        'job-professional-school' => ['model' => \App\Models\JobProfessionalAdmission::class, 'label' => 'Job Professional Growth'],
        'business-growth-school' => ['model' => \App\Models\BusinessGrowthAdmission::class, 'label' => 'Business Growth School'],
        'intro-session' => ['model' => \App\Models\ContactInquiry::class, 'label' => 'Introduction Session'],
    ],

];
