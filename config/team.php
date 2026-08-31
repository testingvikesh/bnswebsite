<?php

return [

    'page' => [
        'title' => 'Meet Our Team',
        'subtitle' => 'The People Behind Business Navachar School',
        'intro' => 'At Business Navachar School (BNS), our greatest strength is our dedicated team of educators, mentors, entrepreneurs, management professionals, and support staff who are committed to building a prosperous India through business education.',
    ],

    'leadership' => [
        'title' => 'Leadership Team',
        'members' => [
            [
                'name' => 'Dr. Mehul Rupani',
                'designation' => 'Founder & Chief Visionary',
                'photo' => 'assets/images/founder/dr-mehul-rupani-founder.png',
                'profile_data' => \App\Support\TeamMemberProfiles::drMehulRupani(),
                'expertise' => [],
                'linkedin' => null,
                'email' => 'info@bnsschool.com',
                'featured' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '[Name]',
                'designation' => 'Chief Executive Officer (CEO)',
                'profile_data' => \App\Support\TeamMemberProfiles::chiefExecutiveOfficer(),
                'expertise' => [],
                'linkedin' => null,
                'email' => null,
                'featured' => false,
                'sort_order' => 2,
            ],
            [
                'name' => '[Name]',
                'designation' => 'Director – Business Navachar School (BNS)',
                'profile_data' => \App\Support\TeamMemberProfiles::directorBns(),
                'expertise' => [],
                'linkedin' => null,
                'email' => null,
                'featured' => false,
                'sort_order' => 3,
            ],
            [
                'name' => '[Name]',
                'designation' => 'Director – Digital & Technology (BNS)',
                'profile_data' => \App\Support\TeamMemberProfiles::directorDigitalBns(),
                'expertise' => [],
                'linkedin' => null,
                'email' => null,
                'featured' => false,
                'sort_order' => 4,
            ],
            [
                'name' => '[Name]',
                'designation' => 'Head – Social Media Marketing & Digital Operations (BNS)',
                'profile_data' => \App\Support\TeamMemberProfiles::headSocialMediaBns(),
                'expertise' => [],
                'linkedin' => null,
                'email' => null,
                'featured' => false,
                'sort_order' => 5,
            ],
            [
                'name' => '[Name]',
                'designation' => 'Head of Marketing (BNS)',
                'profile_data' => \App\Support\TeamMemberProfiles::headMarketingBns(),
                'expertise' => [],
                'linkedin' => null,
                'email' => null,
                'featured' => false,
                'sort_order' => 6,
            ],
            [
                'name' => '[Name]',
                'designation' => 'Marketing Manager (BNS)',
                'profile_data' => \App\Support\TeamMemberProfiles::marketingManagerBns(),
                'expertise' => [],
                'linkedin' => null,
                'email' => null,
                'featured' => false,
                'sort_order' => 7,
            ],
        ],
    ],

    'academic' => [
        'title' => 'Academic Team',
        'members' => [
            [
                'name' => '[Faculty Name]',
                'designation' => 'Academic Mentor',
                'expertise' => ['Business Strategy', 'Leadership', 'Entrepreneurship'],
            ],
            [
                'name' => '[Faculty Name]',
                'designation' => 'Business Mentor',
                'expertise' => ['Marketing', 'Sales', 'Branding'],
            ],
            [
                'name' => '[Faculty Name]',
                'designation' => 'Finance Mentor',
                'expertise' => ['Finance', 'Investment', 'Wealth Creation'],
            ],
            [
                'name' => '[Faculty Name]',
                'designation' => 'Innovation Mentor',
                'expertise' => ['Innovation', 'Startup', 'Design Thinking'],
            ],
        ],
    ],

    'advisory' => [
        'title' => 'Advisory Board',
    ],

    'sponsors_page' => [
        'title' => 'Meet Our Sponsors',
        'subtitle' => 'Our Partners & Supporters',
        'intro' => 'Business Navachar School is supported by trusted sponsors and venue partners who contribute financial expertise, professional guidance, entrepreneurship experience, and learning infrastructure to strengthen our mission of business education for every Indian.',
    ],

    'sponsors' => [
        'title' => 'Meet Our Sponsors',
        'subtitle' => 'Santacruz leadership supporting Business Navachar School with financial expertise and professional guidance.',
        'section_label' => 'Santacruz',
        'members' => [
            [
                'name' => 'CA Sanjay Doshi',
                'designation' => 'President',
                'photo' => 'assets/images/team/sponsors/ca-sanjay-doshi.png',
                'profile' => 'Associated with Stock markets, Mutual Funds & Insurance industry since last 30 years.',
                'sort_order' => 1,
            ],
            [
                'name' => 'CA Pankaj Bavishi',
                'designation' => 'Vice President',
                'photo' => 'assets/images/team/sponsors/ca-pankaj-bavishi.png',
                'profile' => 'Associated with Audit, Income Tax and Specialises in Charitable trust audits.',
                'sort_order' => 2,
            ],
        ],
    ],

    'venue_partner' => [
        'title' => 'Venue Partner',
        'subtitle' => 'Industry leader supporting BNS with entrepreneurship expertise, strategic guidance, and experiential learning opportunities.',
        'supported_by' => [
            'section_label' => 'Support',
            'title' => 'Supported By',
            'subtitle' => 'With the generous support of trusted charitable institutions and families committed to business education for every Indian.',
            'items' => [
                'Manjulaben Kantilal Parekh Charitable Trust',
                'Rupaben Hareshbhai Parekh Pariwar',
            ],
        ],
        'venue' => [
            'title' => 'Venue Partner (Address)',
            'location' => 'Shree Vardhaman Sthanakwasi Jain Shravak Sangh Santacruz West',
            'address_lines' => [
                'Shree Vardhaman Sthanakwasi Jain Shravak Sangh Santacruz West',
                'Smt K D Mehta Dharmasthanak',
                'M D Mehta Chowk',
                'P M Road, Near Hi Life Mall',
                'Santacruz West',
                'Mumbai 400054',
            ],
            'photo' => 'assets/images/team/venue-partner/venue-shri-vardhaman-jain-shravak-sangh.png',
            'photo_alt' => 'Shree Vardhaman Sthanakwasi Jain Shravak Sangh Santacruz West — BNS Venue Partner',
        ],
        'member' => [
            'name' => 'Haresh Parekh',
            'designation' => 'Supporter Sponsor',
            'photo' => 'assets/images/team/venue-partner/haresh-parekh.png',
            'profile_data' => \App\Support\TeamMemberProfiles::hareshParekh(),
        ],
    ],

    'collaboration' => [
        'title' => 'Faculties of IIM',
        'badge' => 'Academic Collaboration',
        'description' => 'Business Navachar School has an Academic Collaboration with Faculties of IIM for mentor development, business learning methodologies, and academic excellence.',
    ],

    'operations' => [
        'title' => 'Operations Team',
        'teams' => [
            [
                'name' => 'Admissions Team',
                'description' => 'Helping learners begin their BNS journey.',
                'icon' => 'fas fa-user-plus',
            ],
            [
                'name' => 'Student Success Team',
                'description' => 'Supporting every learner throughout the program.',
                'icon' => 'fas fa-hands-helping',
            ],
            [
                'name' => 'Marketing & Communications Team',
                'description' => 'Building awareness and connecting with aspiring learners.',
                'icon' => 'fas fa-bullhorn',
            ],
            [
                'name' => 'Technology Team',
                'description' => 'Managing Digital Learning Platform, Website & Student Portal.',
                'icon' => 'fas fa-laptop-code',
            ],
            [
                'name' => 'Finance & Administration Team',
                'description' => 'Ensuring smooth operations and learner support.',
                'icon' => 'fas fa-chart-line',
            ],
        ],
    ],

    'values' => [
        'title' => 'Our Team Values',
        'items' => [
            'Integrity',
            'Innovation',
            'Excellence',
            'Collaboration',
            'Leadership',
            'Lifelong Learning',
            'Nation Building',
        ],
    ],

    'join' => [
        'title' => 'Join Our Team',
        'intro' => 'Are you passionate about education, entrepreneurship, innovation, and nation building?',
        'looking_for_label' => 'We are always looking for:',
        'roles' => [
            'Business Mentors',
            'Faculty Members',
            'Industry Experts',
            'Entrepreneurs',
            'Volunteers',
            'Interns',
            'Corporate Partners',
        ],
        'cta_title' => 'Become a Part of the BNS Mission',
        'cta_text' => "Together, let's build a Prosperous India through Business Education.",
        'contact_email' => 'info@bnsschool.com',
    ],

];
