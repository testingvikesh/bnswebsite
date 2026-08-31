<?php

/**
 * Rich UI payloads for Business Coach mail boxes (Student-message style cards).
 *
 * @return array<string, array<string, mixed>>
 */
$website = 'https://businessnavacharschool.com';

$motto = [
    'Learn Business.',
    'Build Business.',
    'Create Employment.',
    'Build Developed India.',
];

$signers = [
    [
        'name' => 'Dr. Mehul Rupani',
        'role' => 'Founder',
        'org' => 'Business Navachar School (BNS)',
        'phone' => '+91 94272 20997',
    ],
];

$reels = [
    [
        'emoji' => '🎬',
        'label' => 'BNS Introduction Reel',
        'url' => 'https://www.instagram.com/reel/DacwXpDkThB/?igsh=bmRwZmsybnloNnZn',
    ],
    [
        'emoji' => '🎥',
        'label' => 'BNS Seminar Reel',
        'url' => 'https://youtube.com/shorts/2i2AH0Aj_LY?si=FzxieiV-v6Bppw8W',
    ],
];

$meetingFields = [
    ['icon' => 'fas fa-calendar-alt', 'label' => 'Date', 'value' => '______________________'],
    ['icon' => 'fas fa-calendar-day', 'label' => 'Day', 'value' => '______________________'],
    ['icon' => 'fas fa-clock', 'label' => 'Time', 'value' => '______________________'],
    ['icon' => 'fas fa-video', 'label' => 'Platform', 'value' => 'Google Meet'],
    ['icon' => 'fas fa-link', 'label' => 'Google Meet Link', 'value' => '_____________________________________'],
];

$pack = static function (array $extra, bool $withReels = true) use ($website, $motto, $signers, $reels): array {
    return array_merge([
        'website' => $website,
        'motto' => $motto,
        'signers' => $signers,
        'reels' => $withReels ? $reels : [],
    ], $extra);
};

return [

    'coach-welcome' => $pack([
        'eyebrow' => 'Welcome',
        'headline' => 'Welcome to Business Navachar School (BNS)!',
        'hero_icon' => 'fas fa-handshake',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Thank you for submitting your Business Coach Application Form.',
            'We sincerely appreciate your interest in becoming a part of Business Navachar School (BNS).',
        ],
        'highlight' => [
            'icon' => 'fas fa-check-circle',
            'title' => 'Your application has been successfully received.',
            'text' => 'Thank you for taking the first step towards becoming a part of our growing Business Education Mission.',
        ],
        'cards' => [
            [
                'emoji' => '🌟',
                'title' => 'About Business Navachar School (BNS)',
                'body' => [
                    'Business Navachar School (BNS) is India\'s Weekly Business School and a unique Business Learning Ecosystem dedicated to developing the next generation of Entrepreneurs, Business Leaders, and Job Creators.',
                    'BNS provides:',
                ],
                'checks' => [
                    'Weekly Business Coaching',
                    'Business Learning Ecosystem',
                    'Business Networking Ecosystem',
                    'Business Incubation Ecosystem',
                    'AI & Future Business Learning',
                    'Startup to Scale-up Guidance',
                    'Business ABCD to IPO Journey',
                ],
                'after' => [
                    'Our mission is to empower Students, Youth, Women, MSMEs, Startups, Business Owners, and Working Professionals through Practical Business Education, Mentorship, and Business Growth Opportunities.',
                    'We are delighted that you have shown your willingness to contribute to this national mission as a Business Coach.',
                    'Together, let us inspire, mentor, and empower thousands of aspiring entrepreneurs across India.',
                ],
            ],
            [
                'emoji' => '📞',
                'title' => 'Need Any Assistance?',
                'body' => [
                    'Our team will be happy to assist you whenever required.',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], false),

    'coach-after-registration' => $pack([
        'eyebrow' => 'Registration',
        'headline' => 'Thank You for Your Registration!',
        'hero_icon' => 'fas fa-user-check',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Thank you for successfully registering as a Business Coach with Business Navachar School (BNS).',
            'We are delighted to welcome you to the growing BNS Family.',
        ],
        'highlight' => [
            'icon' => 'fas fa-check-circle',
            'title' => 'Your registration has been successfully received.',
            'text' => 'Thank you for joining us in our mission to promote Business Education, Entrepreneurship, Innovation, and Employment Generation.',
        ],
        'cards' => [
            [
                'emoji' => '🌟',
                'title' => 'About Business Navachar School (BNS)',
                'body' => [
                    'Business Navachar School (BNS) is India\'s Weekly Business School and a unique Business Learning Ecosystem committed to empowering Students, Youth, Women, MSMEs, Startups, Business Owners, and Working Professionals.',
                    'Through Weekly Business Coaching, Business Networking, Business Incubation, and Practical Business Learning, we aim to create the next generation of Entrepreneurs and Business Leaders.',
                ],
            ],
            [
                'emoji' => '🌟',
                'title' => 'As a Business Coach, you will become a part of:',
                'checks' => [
                    'India\'s Business Education Mission',
                    'Business Coaching Ecosystem',
                    'Business Networking Ecosystem',
                    'Business Incubation Ecosystem',
                    'Entrepreneurship Development Movement',
                    'Viksit Bharat 2047 Mission',
                ],
                'after' => [
                    'We look forward to your valuable knowledge, experience, and contribution in shaping the future of aspiring entrepreneurs.',
                    'Together, let us build a stronger business ecosystem for India.',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], false),

    'coach-application-received' => $pack([
        'eyebrow' => 'Confirmation',
        'headline' => 'Application Received Successfully!',
        'hero_icon' => 'fas fa-file-alt',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Thank you for submitting your Business Coach Application to Business Navachar School (BNS).',
            'We are pleased to inform you that your application has been successfully received.',
            'Thank you for showing your interest in becoming a part of India\'s Weekly Business School and our growing Business Education Movement.',
        ],
        'cards' => [
            [
                'emoji' => '🌟',
                'title' => 'About Business Navachar School (BNS)',
                'body' => [
                    'Business Navachar School (BNS) is a unique Business Learning Ecosystem dedicated to developing Entrepreneurs, Business Leaders, and Job Creators.',
                    'Our mission is to empower Students, Youth, Women, MSMEs, Startups, Business Owners, and Working Professionals through:',
                ],
                'checks' => [
                    'Weekly Business Coaching',
                    'Business Learning Ecosystem',
                    'Business Networking Ecosystem',
                    'Business Incubation Ecosystem',
                    'AI & Future Business Learning',
                    'Business ABCD to IPO Journey',
                ],
            ],
            [
                'emoji' => '📋',
                'title' => 'What Happens Next?',
                'body' => [
                    'Our team will communicate with you regarding the upcoming Business Coach Meetings, Orientation Sessions, and other important updates.',
                    'Kindly stay connected with us.',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], false),

    'coach-meeting-invitation' => $pack([
        'eyebrow' => 'Invitation',
        'headline' => 'Business Coach Meeting Invitation',
        'hero_icon' => 'fas fa-calendar-plus',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'Thank you for your interest in becoming a Business Coach with Business Navachar School (BNS).',
            'We are pleased to invite you to attend an Online Business Coach Meeting where we will introduce the vision, mission, and opportunities of BNS.',
        ],
        'cards' => [
            [
                'emoji' => '📅',
                'title' => 'Meeting Details',
                'fields' => $meetingFields,
            ],
            [
                'emoji' => '🌟',
                'title' => 'Meeting Agenda',
                'checks' => [
                    'Introduction to Business Navachar School (BNS)',
                    'Vision & Mission',
                    'Business Coach Role & Responsibilities',
                    'Business Coaching Ecosystem',
                    'Business Networking Ecosystem',
                    'Business Incubation Ecosystem',
                    'Business Coach Opportunities',
                    'Question & Answer Session',
                ],
                'after' => [
                    'Your presence will be highly valuable. We look forward to meeting you and exploring how we can work together to empower the next generation of entrepreneurs.',
                    'Kindly join the meeting 5–10 minutes before the scheduled time.',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], false),

    'coach-calendar-invite' => $pack([
        'eyebrow' => 'Calendar',
        'headline' => 'Calendar Invitation – Business Coach Meeting',
        'hero_icon' => 'fas fa-calendar-check',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'This is a gentle reminder to save the Business Coach Meeting in your calendar so you don\'t miss this important session.',
        ],
        'cards' => [
            [
                'emoji' => '📅',
                'title' => 'Meeting Details',
                'fields' => $meetingFields,
            ],
            [
                'emoji' => '📌',
                'title' => 'Action Required',
                'highlight_inline' => 'Kindly add this meeting to your Calendar and set a reminder.',
                'body' => [
                    'We recommend joining the meeting 5–10 minutes before the scheduled time.',
                ],
            ],
            [
                'emoji' => '🌟',
                'title' => 'During the Meeting, You Will Learn About:',
                'checks' => [
                    'Business Navachar School (BNS)',
                    'Vision & Mission',
                    'Business Coach Opportunity',
                    'Business Coaching Ecosystem',
                    'Business Networking Ecosystem',
                    'Business Incubation Ecosystem',
                    'Question & Answer Session',
                ],
                'after' => [
                    'We look forward to your valuable presence and active participation.',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], true),

    'coach-required-documents' => $pack([
        'eyebrow' => 'Documents',
        'headline' => 'Required Documents Reminder',
        'hero_icon' => 'fas fa-folder-open',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'This is a gentle reminder to kindly keep the following documents ready and submit them at your earliest convenience.',
        ],
        'cards' => [
            [
                'emoji' => '📄',
                'title' => 'Required Documents',
                'checks' => [
                    'Passport Size Photograph',
                    'Aadhaar Card',
                    'PAN Card',
                    'Updated Resume / CV',
                    'Educational Qualification Certificates',
                    'Experience Certificates (If Available)',
                    'Business / Company Profile (If Applicable)',
                    'Visiting Card (If Available)',
                    'LinkedIn Profile Link',
                    'Website / Social Media Links (If Available)',
                    'GST Certificate (If Applicable)',
                ],
                'after' => [
                    'Kindly ensure that all documents are clear and complete.',
                    'These documents will help us maintain accurate records and complete the Business Coach onboarding process smoothly.',
                ],
            ],
            [
                'emoji' => '📤',
                'title' => 'Submit Your Documents',
                'fields' => [
                    ['icon' => 'fas fa-link', 'label' => 'Submission Link', 'value' => '______________________________'],
                ],
            ],
            [
                'emoji' => '📞',
                'title' => 'Need Any Assistance?',
                'body' => [
                    'Please feel free to contact our team.',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], false),

    'coach-one-day-before' => $pack([
        'eyebrow' => 'Reminder',
        'headline' => 'One Day Before Reminder – Business Coach Meeting',
        'hero_icon' => 'fas fa-bell',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'This is a friendly reminder that your Business Coach Meeting is scheduled for tomorrow.',
            'We are excited to connect with you and share the vision, mission, and opportunities of Business Navachar School (BNS).',
        ],
        'cards' => [
            [
                'emoji' => '📅',
                'title' => 'Meeting Details',
                'fields' => $meetingFields,
            ],
            [
                'emoji' => '📌',
                'title' => 'Before Joining the Meeting',
                'checks' => [
                    'Save the meeting in your Calendar.',
                    'Keep your internet connection ready.',
                    'Join the meeting 5–10 minutes early.',
                    'Keep a Notebook & Pen ready.',
                    'Attend from a quiet place for a better experience.',
                ],
            ],
            [
                'emoji' => '🌟',
                'title' => 'During the Meeting, You Will Learn About:',
                'checks' => [
                    'Business Navachar School (BNS)',
                    'Vision & Mission',
                    'Business Coach Opportunity',
                    'Business Coaching Ecosystem',
                    'Business Networking Ecosystem',
                    'Business Incubation Ecosystem',
                    'Question & Answer Session',
                ],
                'after' => [
                    'We look forward to your valuable presence.',
                    'See you tomorrow!',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], true),

    'coach-two-hours-before' => $pack([
        'eyebrow' => 'Final Reminder',
        'headline' => 'Two Hours Before Reminder – Business Coach Meeting',
        'hero_icon' => 'fas fa-hourglass-half',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'This is a friendly reminder that our Business Coach Meeting will begin in 2 Hours.',
            'We look forward to welcoming you and interacting with you.',
        ],
        'cards' => [
            [
                'emoji' => '📅',
                'title' => 'Meeting Details',
                'fields' => $meetingFields,
            ],
            [
                'emoji' => '📌',
                'title' => 'Before You Join',
                'checks' => [
                    'Keep your Laptop / Mobile ready.',
                    'Ensure a Stable Internet Connection.',
                    'Keep a Notebook & Pen ready.',
                    'Join the meeting 5–10 minutes before the scheduled time.',
                    'Attend from a Quiet Place for the best experience.',
                ],
            ],
            [
                'emoji' => '🌟',
                'title' => 'Today\'s Meeting Includes',
                'checks' => [
                    'Business Navachar School (BNS) Introduction',
                    'Vision & Mission',
                    'Business Coach Opportunity',
                    'Business Coaching Ecosystem',
                    'Business Networking Ecosystem',
                    'Business Incubation Ecosystem',
                    'Open Question & Answer Session',
                ],
                'after' => [
                    'We are excited to meet you shortly.',
                    'Thank you for being a part of Business Navachar School (BNS).',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], true),

    'coach-thank-you-attending' => $pack([
        'eyebrow' => 'Gratitude',
        'headline' => 'Thank You for Attending the Business Coach Meeting!',
        'hero_icon' => 'fas fa-heart',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'Thank you for taking the time to attend today\'s Business Coach Meeting.',
            'Your valuable presence, participation, and interest in Business Navachar School (BNS) are truly appreciated.',
        ],
        'cards' => [
            [
                'emoji' => '🌟',
                'title' => 'We hope the meeting provided you with a clear understanding of:',
                'checks' => [
                    'Business Navachar School (BNS)',
                    'Our Vision & Mission',
                    'Business Coach Opportunity',
                    'Business Coaching Ecosystem',
                    'Business Networking Ecosystem',
                    'Business Incubation Ecosystem',
                ],
                'after' => [
                    'We believe that, together, we can inspire, mentor, and empower thousands of aspiring entrepreneurs and contribute towards building a stronger business ecosystem for India.',
                ],
            ],
        ],
        'closing' => [
            'If you have any questions or require any further information, our team will be happy to assist you.',
            'We look forward to staying connected and meeting you again soon.',
            'With Warm Regards,',
        ],
    ], true),

    'coach-feedback-form' => $pack([
        'eyebrow' => 'Feedback',
        'headline' => 'Business Coach Meeting Feedback Form',
        'hero_icon' => 'fas fa-clipboard-list',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'Thank you for attending the Business Coach Meeting.',
            'Your valuable feedback is very important to us and will help us improve our future meetings and programs.',
        ],
        'cards' => [
            [
                'emoji' => '📝',
                'title' => 'Complete the Meeting Feedback Form',
                'highlight_inline' => 'Kindly take 2 minutes to complete the Meeting Feedback Form.',
                'fields' => [
                    ['icon' => 'fas fa-link', 'label' => 'Feedback Form', 'value' => '_____________________________________'],
                ],
            ],
            [
                'emoji' => '🌟',
                'title' => 'Your feedback will help us understand:',
                'checks' => [
                    'Your Overall Meeting Experience',
                    'Content & Presentation Quality',
                    'Meeting Usefulness',
                    'Suggestions & Recommendations',
                    'Future Expectations',
                ],
                'after' => [
                    'Thank you once again for your valuable time, participation, and support.',
                    'We look forward to building a long-term association with you.',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], true),

    'coach-join-now' => $pack([
        'eyebrow' => 'Join Now',
        'headline' => 'Join Meeting Now – Business Coach Meeting',
        'hero_icon' => 'fas fa-video',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'The Business Coach Meeting is starting now.',
            'We are ready to welcome you.',
            'Kindly join the meeting immediately using the Google Meet link below.',
        ],
        'cards' => [
            [
                'emoji' => '📅',
                'title' => 'Meeting Details',
                'fields' => $meetingFields,
            ],
            [
                'emoji' => '📌',
                'title' => 'Before You Join',
                'checks' => [
                    'Join the meeting immediately.',
                    'Keep your Camera ON (if possible).',
                    'Keep your Microphone on Mute unless speaking.',
                    'Keep a Notebook & Pen ready.',
                    'Attend from a Quiet Place.',
                ],
            ],
            [
                'emoji' => '🌟',
                'title' => 'Today\'s Meeting Includes',
                'checks' => [
                    'Business Navachar School (BNS) Introduction',
                    'Vision & Mission',
                    'Business Coach Opportunity',
                    'Business Coaching Ecosystem',
                    'Business Networking Ecosystem',
                    'Business Incubation Ecosystem',
                    'Interactive Question & Answer Session',
                ],
                'after' => [
                    'We are excited to welcome you.',
                    'See you in the meeting!',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], true),

    'coach-next-step' => $pack([
        'eyebrow' => 'Next Steps',
        'headline' => 'Next Step Information – Business Coach',
        'hero_icon' => 'fas fa-road',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'Thank you for attending the Business Coach Meeting.',
            'We sincerely appreciate your valuable time, participation, and interest in becoming a part of Business Navachar School (BNS).',
        ],
        'cards' => [
            [
                'emoji' => '📌',
                'title' => 'Your Next Steps',
                'checks' => [
                    'Stay connected with Business Navachar School (BNS) for upcoming updates and communications.',
                    'Keep an eye on your WhatsApp and Email for important announcements and meeting invitations.',
                    'Explore our website and learn more about the BNS Vision, Mission, and Business Ecosystem.',
                    'Watch the BNS Introduction and Seminar Reels to gain a deeper understanding of our mission.',
                    'We look forward to interacting with you in our upcoming meetings, training sessions, and collaborative initiatives.',
                ],
                'after' => [
                    'We look forward to building a long-term association with you and working together to strengthen Business Education, Entrepreneurship, Business Coaching, and Business Incubation across India.',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], true),

    'coach-meeting-summary' => $pack([
        'eyebrow' => 'Summary',
        'headline' => 'Business Coach Meeting Summary',
        'hero_icon' => 'fas fa-list-alt',
        'greeting' => 'Dear Business Coach,',
        'lead' => [
            'Greetings from Business Navachar School (BNS)!',
            'Thank you for attending the Business Coach Meeting.',
            'It was a pleasure interacting with you and sharing the vision of Business Navachar School (BNS).',
        ],
        'cards' => [
            [
                'emoji' => '📋',
                'title' => 'Meeting Summary',
                'body' => [
                    'During today\'s meeting, the following topics were discussed:',
                ],
                'numbered' => [
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                ],
            ],
            [
                'emoji' => '🌟',
                'title' => 'Key Takeaways',
                'blank_checks' => [
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                    '___________________________________________',
                ],
                'after' => [
                    'Thank you once again for your valuable time, active participation, and valuable suggestions.',
                    'We look forward to building a long-term association with you and working together to strengthen Business Education, Business Coaching, Business Networking, and Business Incubation across India.',
                ],
            ],
        ],
        'closing' => ['With Warm Regards,'],
    ], true),

];
