<?php

return [
    // Used by the sitewide "Who are you?" quick-register modal (bnsQuickRegisterModal).
    // Ids match the register_program_id emitted by HomeAudienceJourneyService for each
    // of the 5 real BNS audience programs shown under "Explore Programs".
    'quick_modal_programs' => [
        [
            'id' => 'student-school',
            'title' => 'School Student',
            'contact_program' => 'School Students Program',
            'category' => 'Student',
        ],
        [
            'id' => 'business-growth-school',
            'title' => 'Growth Batch',
            'contact_program' => 'Business Owners & Business Professionals Program',
            'category' => 'Business Owner',
        ],
        [
            'id' => 'youth-school',
            'title' => 'College Youth',
            'contact_program' => 'College Students Program',
            'category' => 'College Student',
        ],
        [
            'id' => 'job-business-batch',
            'title' => 'Women Batch',
            'contact_program' => 'Women Entrepreneurs Program',
            'category' => 'Woman Entrepreneur',
        ],
        [
            'id' => 'business-job-professional-batch',
            'title' => 'Business & Job Professional',
            'contact_program' => 'Business and Job Professional Batch Program',
            'category' => 'Business Owner',
        ],
    ],

    // Used by the dedicated /register page (full multi-field admission forms per program).
    // Reduced to the same 5 official "batches" used sitewide (see quick_modal_programs above).
    // The "Business & Job Professional" box maps to two existing full admission forms
    // (job-professional-school / working-women-school); clicking it lets the visitor pick
    // which detailed form to complete via the "combo" sub-options below.
    'programs' => [
        [
            'id' => 'student-school',
            'program_slug' => 'school-student',
            'num' => '01',
            'title' => 'School Student',
            'title_top' => 'SCHOOL',
            'title_main' => 'Student',
            'subtitle' => 'Std 6 to Std 11 students',
            'icon' => 'fas fa-book-open',
            'image_url' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=900&q=80',
            'image_alt' => 'School students learning together',
            'card_theme' => ['bg' => '#4a0e1c', 'accent' => '#ffb08f'],
            'contact_program' => 'School Students Program',
            'category' => 'Student',
        ],
        [
            'id' => 'business-growth-school',
            'program_slug' => 'growth-batch',
            'num' => '02',
            'title' => 'Growth Batch',
            'title_top' => 'GROWTH',
            'title_main' => 'Batch',
            'subtitle' => 'Owners, founders & family business leaders',
            'icon' => 'fas fa-chart-line',
            'image_url' => 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=900&q=80',
            'image_alt' => 'Business growth and leadership',
            'card_theme' => ['bg' => '#2d1b33', 'accent' => '#f4a8a0'],
            'contact_program' => 'Business Owners & Business Professionals Program',
            'category' => 'Business Owner',
        ],
        [
            'id' => 'youth-school',
            'program_slug' => 'college-youth',
            'num' => '03',
            'title' => 'College Youth',
            'title_top' => 'COLLEGE',
            'title_main' => 'Youth',
            'subtitle' => 'College students, graduates & young professionals',
            'icon' => 'fas fa-user-graduate',
            'image_url' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=900&q=80',
            'image_alt' => 'College youth in campus learning',
            'card_theme' => ['bg' => '#1a2f4d', 'accent' => '#fbbf24'],
            'contact_program' => 'College Students Program',
            'category' => 'College Student',
        ],
        [
            'id' => 'women-school',
            'program_slug' => 'women-batch',
            'num' => '04',
            'title' => 'Women Batch',
            'title_top' => 'WOMEN',
            'title_main' => 'Batch',
            'subtitle' => 'Housewives, entrepreneurs & working women',
            'icon' => 'fas fa-female',
            'image_url' => 'https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=900&q=80',
            'image_alt' => 'Women entrepreneurs learning together',
            'card_theme' => ['bg' => '#4a0e1c', 'accent' => '#ffb08f'],
            'contact_program' => 'Women Entrepreneurs Program',
            'category' => 'Woman Entrepreneur',
        ],
        [
            'id' => 'business-job-professional-batch',
            'program_slug' => 'business-job-professional-batch',
            'num' => '05',
            'title' => 'Business & Job Professional',
            'title_top' => 'BUSINESS & JOB',
            'title_main' => 'Professional',
            'subtitle' => 'Job Excellence School for professionals & Business Empire School for owners',
            'icon' => 'fas fa-briefcase',
            'image_url' => 'https://images.unsplash.com/photo-1551836022-d5d88e9218df?auto=format&fit=crop&w=900&q=80',
            'image_alt' => 'Business owners and professionals in a meeting',
            'card_theme' => ['bg' => '#1e3a5f', 'accent' => '#60a5fa'],
            'contact_program' => 'Business and Job Professional Batch Program',
            'category' => 'Business Owner',
            'combo' => [
                [
                    'id' => 'job-professional-school',
                    'label' => 'Job Professional Growth',
                    'desc' => 'Employees, officers & professionals',
                    'icon' => 'fas fa-user-tie',
                ],
                [
                    'id' => 'working-women-school',
                    'label' => 'Working Women Leadership',
                    'desc' => 'Executives, managers & career-focused women',
                    'icon' => 'fas fa-briefcase',
                ],
            ],
        ],
    ],
];
