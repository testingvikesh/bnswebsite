<?php

return [

    // Permanent member refund after verification (₹11,800 − ₹8,640)
    'scholarship_amount' => (int) env('PAY_NOW_SCHOLARSHIP_AMOUNT', 3160),

    // Gateway charge = ₹10,000 + 18% GST = ₹11,800 (everyone pays full first)
    'payment_amount' => env('PAYMENT_FEE_INTRO_SESSION', env('PAYMENT_DEFAULT_AMOUNT', env('PAYMENT_AMOUNT', '11800.00'))),

    // Shown on Pay Now button
    'amount_label' => env('PAY_NOW_AMOUNT_LABEL', '11,800'),

    // Same 5 programs as home "Are you" cards (used for labels in reporting/attendance)
    'programs' => [
        'School Students Program' => 'School Student',
        'Business Owners & Business Professionals Program' => 'Growth Batch',
        'College Students Program' => 'College Youth',
        'Women Entrepreneurs Program' => 'Women Batch',
        'Business and Job Professional Batch Program' => 'Business & Job Professional',
    ],

    // Programs selectable on the Pay Now modal form (only Growth Batch for now)
    'form_programs' => [
        'Business Owners & Business Professionals Program' => 'Growth Batch (Mix Batch)',
    ],

    'banner' => [
        'payment_btn' => 'Pay Now',
        'footer' => 'Business Navachar School (BNS)',
        'tagline' => 'Learn Business. Build Business. Create Employment. Build India.',
    ],

    /*
    | 3-language admission instructions shown after Pay Now click,
    | before the payment form.
    */
    'instructions' => [
        'en' => [
            'label' => 'English',
            'title' => 'IMPORTANT ADMISSION INSTRUCTIONS',
            'subtitle' => 'Special Fee Benefit for Permanent Members of Santacruz Jain Upashray',
            'sections' => [
                [
                    'heading' => 'For Non-Members',
                    'lines' => [
                        'Course Fee: ₹10,000 + 18% GST',
                        'Total Payable Amount: ₹11,800',
                    ],
                ],
                [
                    'heading' => 'For Permanent Members of Santacruz Jain Upashray',
                    'lines' => [
                        'Special Course Fee: ₹7,200 + 18% GST',
                        'Effective Fee: ₹8,640',
                    ],
                ],
                [
                    'heading' => 'Admission Process',
                    'lines' => [
                        'All participants are requested to first pay the Full Admission Fee of ₹11,800 through the online payment gateway.',
                        'After successful payment, Permanent Members of Santacruz Jain Upashray must upload their Permanent Membership Proof on the website.',
                        'Once the Membership is successfully verified, a Refund of ₹3,160 will be processed to the same account/payment method.',
                    ],
                ],
                [
                    'heading' => 'Important Notes',
                    'lines' => [
                        'The ₹3,160 Refund is applicable only for verified Permanent Members of Santacruz Jain Upashray.',
                        'Participants who are not Permanent Members are required to pay the Full Fee of ₹11,800, and no refund will be applicable.',
                        'Please upload your Membership Proof immediately after completing the payment to avoid any delay in the refund process.',
                    ],
                ],
            ],
            'thanks' => 'Thank you for your cooperation.',
            'brand' => 'Business Navachar School (BNS)',
            'tagline' => 'Learn Business. Build Business. Create Employment. Build India.',
        ],
        'gu' => [
            'label' => 'ગુજરાતી',
            'title' => 'મહત્વપૂર્ણ એડમિશન સૂચના',
            'subtitle' => 'સાંતાક્રુઝ જૈન ઉપાશ્રયના કાયમી સભ્યો માટે વિશેષ ફી લાભ',
            'sections' => [
                [
                    'heading' => 'ઉપાશ્રયના સભ્ય ન હોય તેવા ઉમેદવારો માટે',
                    'lines' => [
                        'કોર્સ ફી: ₹10,000 + 18% GST',
                        'કુલ ચૂકવવાની રકમ: ₹11,800',
                    ],
                ],
                [
                    'heading' => 'સાંતાક્રુઝ જૈન ઉપાશ્રયના કાયમી સભ્યો માટે',
                    'lines' => [
                        'વિશેષ કોર્સ ફી: ₹7,200 + 18% GST',
                        'અસરકારક ફી: ₹8,640',
                    ],
                ],
                [
                    'heading' => 'એડમિશન પ્રક્રિયા',
                    'lines' => [
                        'તમામ ઉમેદવારોએ સૌપ્રથમ વેબસાઇટ દ્વારા ₹11,800 ની સંપૂર્ણ ફી ભરવાની રહેશે.',
                        'ફી ભર્યા બાદ સાંતાક્રુઝ જૈન ઉપાશ્રયના કાયમી સભ્યોએ પોતાની Permanent Membership Proof વેબસાઇટ પર અપલોડ કરવો.',
                        'સભ્યપદની ચકાસણી પૂર્ણ થયા બાદ ₹3,160 ની રકમ તે જ બેંક એકાઉન્ટ / પેમેન્ટ માધ્યમમાં પરત આપવામાં આવશે.',
                    ],
                ],
                [
                    'heading' => 'મહત્વપૂર્ણ નોંધ',
                    'lines' => [
                        '₹3,160 નું રિફંડ માત્ર સાંતાક્રુઝ જૈન ઉપાશ્રયના ચકાસાયેલ કાયમી સભ્યોને જ મળશે.',
                        'જે ઉમેદવાર કાયમી સભ્ય નથી તેમણે ₹11,800 ની સંપૂર્ણ ફી ભરવાની રહેશે અને તેમને કોઈ રિફંડ મળશે નહીં.',
                        'ફી ભર્યા બાદ કૃપા કરીને શક્ય તેટલી વહેલી તકે Membership Proof અપલોડ કરશો જેથી રિફંડમાં કોઈ વિલંબ ન થાય.',
                    ],
                ],
            ],
            'thanks' => 'આપના સહકાર બદલ આભાર.',
            'brand' => 'Business Navachar School (BNS)',
            'tagline' => 'Learn Business. Build Business. Create Employment. Build India.',
        ],
        'hi' => [
            'label' => 'हिन्दी',
            'title' => 'महत्वपूर्ण प्रवेश सूचना',
            'subtitle' => 'सांताक्रूज़ जैन उपाश्रय के स्थायी सदस्यों के लिए विशेष शुल्क लाभ',
            'sections' => [
                [
                    'heading' => 'सामान्य प्रवेश शुल्क (सभी प्रतिभागियों के लिए)',
                    'lines' => [
                        'कोर्स फीस: ₹10,000 + 18% GST',
                        'कुल देय राशि: ₹11,800',
                    ],
                ],
                [
                    'heading' => 'सांताक्रूज़ जैन उपाश्रय के स्थायी सदस्यों के लिए विशेष लाभ',
                    'lines' => [
                        'विशेष कोर्स फीस: ₹7,200 + 18% GST',
                        'प्रभावी फीस: ₹8,640',
                        'रिफंड राशि: ₹3,160',
                    ],
                ],
                [
                    'heading' => 'प्रवेश प्रक्रिया',
                    'lines' => [
                        'सभी प्रतिभागियों को पहले वेबसाइट के माध्यम से ₹11,800 का पूर्ण भुगतान करना होगा।',
                        'भुगतान के बाद, सांताक्रूज़ जैन उपाश्रय के स्थायी सदस्य अपनी Permanent Membership Proof वेबसाइट पर अपलोड करें।',
                        'सदस्यता सत्यापित होने के बाद ₹3,160 की राशि उसी बैंक खाते / भुगतान माध्यम में वापस कर दी जाएगी।',
                    ],
                ],
                [
                    'heading' => 'महत्वपूर्ण सूचना',
                    'lines' => [
                        '₹3,160 का रिफंड केवल सत्यापित स्थायी सदस्यों को ही मिलेगा।',
                        'जो प्रतिभागी सांताक्रूज़ जैन उपाश्रय के स्थायी सदस्य नहीं हैं, उन्हें ₹11,800 की पूर्ण फीस का भुगतान करना होगा तथा उन्हें कोई रिफंड नहीं मिलेगा।',
                        'कृपया भुगतान के तुरंत बाद अपना Membership Proof अपलोड करें, ताकि रिफंड प्रक्रिया शीघ्र पूरी की जा सके।',
                    ],
                ],
            ],
            'thanks' => 'आपके सहयोग के लिए धन्यवाद।',
            'brand' => 'Business Navachar School (BNS)',
            'tagline' => 'Learn Business. Build Business. Create Employment. Build India.',
        ],
    ],

];
