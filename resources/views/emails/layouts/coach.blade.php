@php
    $d = is_array($data ?? null) ? $data : [];
    $eyebrow = (string) ($d['eyebrow'] ?? 'Business Coach');
    $headline = (string) ($d['headline'] ?? 'Business Coach Message');
    $greeting = (string) ($d['greeting'] ?? '');
    $lead = is_array($d['lead'] ?? null) ? $d['lead'] : [];
    $highlight = is_array($d['highlight'] ?? null) ? $d['highlight'] : null;
    $cards = is_array($d['cards'] ?? null) ? $d['cards'] : [];
    $reels = is_array($d['reels'] ?? null) ? $d['reels'] : [];
    $website = (string) ($d['website'] ?? '');
    $closing = is_array($d['closing'] ?? null) ? $d['closing'] : [];
    $signers = is_array($d['signers'] ?? null) ? $d['signers'] : [];
    $motto = is_array($d['motto'] ?? null) ? $d['motto'] : [];

    $checkIcons = ['✅', '🌟', '🚀', '💼', '📈', '🏢', '💡', '🎯', '🤝', '🌐', '🛠', '📋'];
    $fieldIcons = [
        'fas fa-calendar-alt' => '📅',
        'fas fa-calendar-day' => '📆',
        'fas fa-clock' => '🕖',
        'fas fa-video' => '💻',
        'fas fa-link' => '🔗',
        'fas fa-circle' => '•',
    ];
@endphp

{{-- Hero --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:separate;">
    <tr>
        <td style="padding:26px 22px 22px;border-radius:18px;text-align:center;color:#ffffff;background:linear-gradient(135deg,#0a1d37 0%,#1e3a5f 48%,#312e81 100%);">
            <p style="margin:0 0 12px;display:inline-block;padding:7px 14px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);font-size:11px;font-weight:800;letter-spacing:0.08em;text-transform:uppercase;color:#ffffff;">
                {{ $eyebrow }}
            </p>
            <h2 style="margin:0;font-size:22px;line-height:1.35;font-weight:800;color:#ffffff !important;">🌟 {{ $headline }}</h2>
        </td>
    </tr>
</table>

{{-- Intro card --}}
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($greeting !== '')
                <p style="margin:0 0 10px;font-size:15px;font-weight:800;color:#0a1d37;">{{ $greeting }}</p>
            @endif
            @foreach($lead as $line)
                @if(trim((string) $line) !== '')
                    <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#334155;">{{ $line }}</p>
                @endif
            @endforeach
            @if($highlight)
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-top:6px;border:1px solid #bbf7d0;border-radius:12px;background:#f0fdf4;">
                    <tr>
                        <td style="padding:12px 14px;">
                            @if(!empty($highlight['title']))
                                <p style="margin:0 0 4px;font-size:14px;font-weight:800;color:#14532d;">{{ $highlight['title'] }}</p>
                            @endif
                            @if(!empty($highlight['text']))
                                <p style="margin:0;font-size:14px;line-height:1.65;color:#166534;">{{ $highlight['text'] }}</p>
                            @endif
                        </td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>

@foreach($cards as $card)
    @php
        $card = is_array($card) ? $card : [];
        $cardTitle = (string) ($card['title'] ?? '');
        $cardEmoji = (string) ($card['emoji'] ?? '✨');
        $cardBody = is_array($card['body'] ?? null) ? $card['body'] : [];
        $cardAfter = is_array($card['after'] ?? null) ? $card['after'] : [];
        $cardChecks = is_array($card['checks'] ?? null) ? $card['checks'] : [];
        $blankChecks = is_array($card['blank_checks'] ?? null) ? $card['blank_checks'] : [];
        $numbered = is_array($card['numbered'] ?? null) ? $card['numbered'] : [];
        $fields = is_array($card['fields'] ?? null) ? $card['fields'] : [];
        $inline = (string) ($card['highlight_inline'] ?? '');
        $hasList = $cardChecks !== [] || $blankChecks !== [] || $numbered !== [] || $fields !== [];
        $sectionLabel = 'Business Coach Point';
        if (stripos($cardTitle, 'Document') !== false) {
            $sectionLabel = 'Required Document';
        } elseif (stripos($cardTitle, 'Agenda') !== false || stripos($cardTitle, 'Learn') !== false || stripos($cardTitle, 'Includes') !== false) {
            $sectionLabel = 'Meeting Highlight';
        } elseif (stripos($cardTitle, 'Step') !== false) {
            $sectionLabel = 'Next Step';
        } elseif (stripos($cardTitle, 'Takeaway') !== false) {
            $sectionLabel = 'Key Takeaway';
        } elseif (stripos($cardTitle, 'Before') !== false) {
            $sectionLabel = 'Before You Join';
        } elseif (stripos($cardTitle, 'Detail') !== false) {
            $sectionLabel = 'Meeting Detail';
        } elseif (stripos($cardTitle, 'part of') !== false) {
            $sectionLabel = 'You Become Part Of';
        } elseif (stripos($cardTitle, 'About') !== false) {
            $sectionLabel = 'BNS Provides';
        } elseif (stripos($cardTitle, 'feedback') !== false || stripos($cardTitle, 'understand') !== false) {
            $sectionLabel = 'Feedback Area';
        } elseif (stripos($cardTitle, 'Summary') !== false) {
            $sectionLabel = 'Discussion Point';
        }
    @endphp

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
        <tr>
            <td style="padding:18px;">
                <h3 style="margin:0 0 14px;font-size:16px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $cardEmoji }} {{ $cardTitle }}</span>
                </h3>

                @if($inline !== '')
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 12px;border:1px solid #fed7aa;border-radius:12px;background:#fff7ed;">
                        <tr>
                            <td style="padding:11px 13px;font-size:14px;font-weight:700;color:#9a3412;line-height:1.55;">{{ $inline }}</td>
                        </tr>
                    </table>
                @endif

                @foreach($cardBody as $line)
                    @if(trim((string) $line) !== '')
                        <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#334155;">{{ $line }}</p>
                    @endif
                @endforeach

                {{-- Meeting detail rows (Learning Journeys style) --}}
                @foreach($fields as $field)
                    @php
                        $field = is_array($field) ? $field : [];
                        $fa = (string) ($field['icon'] ?? 'fas fa-circle');
                        $icon = $fieldIcons[$fa] ?? '📌';
                        $label = (string) ($field['label'] ?? '');
                        $value = (string) ($field['value'] ?? '');
                    @endphp
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="40" valign="middle" style="padding:12px 0 12px 12px;font-size:18px;line-height:1;">{{ $icon }}</td>
                            <td style="padding:12px 12px 12px 4px;">
                                <p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#94a3b8;">{{ $label }}</p>
                                <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;word-break:break-word;">{{ $value }}</p>
                            </td>
                        </tr>
                    </table>
                @endforeach

                {{-- Checklist items as Learning Journeys cards --}}
                @foreach($cardChecks as $index => $check)
                    @php $icon = $checkIcons[$index % count($checkIcons)]; @endphp
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="40" valign="middle" style="padding:12px 0 12px 12px;font-size:18px;line-height:1;">{{ $icon }}</td>
                            <td style="padding:12px 12px 12px 4px;">
                                <p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#94a3b8;">{{ $sectionLabel }}</p>
                                <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;line-height:1.45;">{{ $check }}</p>
                            </td>
                        </tr>
                    </table>
                @endforeach

                @foreach($blankChecks as $index => $check)
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="40" valign="middle" style="padding:12px 0 12px 12px;font-size:18px;line-height:1;">✔️</td>
                            <td style="padding:12px 12px 12px 4px;">
                                <p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#94a3b8;">{{ $sectionLabel }}</p>
                                <p style="margin:0;font-size:14px;font-weight:800;color:#64748b;letter-spacing:0.02em;">{{ $check }}</p>
                            </td>
                        </tr>
                    </table>
                @endforeach

                @foreach($numbered as $index => $row)
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td width="44" valign="middle" style="padding:12px 0 12px 12px;">
                                <span style="display:inline-block;min-width:28px;padding:6px 0;border-radius:8px;background:rgba(253,110,1,0.12);color:#c2410c;font-size:12px;font-weight:800;text-align:center;">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td style="padding:12px 12px 12px 4px;">
                                <p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#94a3b8;">{{ $sectionLabel }}</p>
                                <p style="margin:0;font-size:14px;font-weight:800;color:#64748b;letter-spacing:0.02em;">{{ $row }}</p>
                            </td>
                        </tr>
                    </table>
                @endforeach

                @foreach($cardAfter as $line)
                    @if(trim((string) $line) !== '')
                        <p style="margin:{{ $hasList ? '12px' : '0' }} 0 0;font-size:14px;line-height:1.7;color:#334155;">{{ $line }}</p>
                    @endif
                @endforeach
            </td>
        </tr>
    </table>
@endforeach

@if(count($reels))
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
        <tr>
            <td style="padding:18px;">
                <h3 style="margin:0 0 14px;font-size:16px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">🎬 Watch & Learn</span>
                </h3>
                @foreach($reels as $reel)
                    @php $reel = is_array($reel) ? $reel : []; @endphp
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #fed7aa;border-radius:12px;background:#fff7ed;">
                        <tr>
                            <td width="40" valign="middle" style="padding:12px 0 12px 12px;font-size:18px;">{{ $reel['emoji'] ?? '🎬' }}</td>
                            <td style="padding:12px 12px 12px 4px;">
                                <p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#94a3b8;">Featured Reel</p>
                                <a href="{{ $reel['url'] ?? '#' }}" style="font-size:14px;font-weight:800;color:#0a1d37;text-decoration:none;">{{ $reel['label'] ?? 'Watch Reel' }} →</a>
                            </td>
                        </tr>
                    </table>
                @endforeach
            </td>
        </tr>
    </table>
@endif

@if($website !== '')
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;">
        <tr>
            <td style="text-align:center;">
                <a href="{{ $website }}" style="display:inline-block;padding:13px 22px;border-radius:999px;background:linear-gradient(135deg,#fd8a2e,#fd6e01);color:#ffffff;text-decoration:none;font-weight:800;font-size:14px;">
                    🌐 {{ preg_replace('#^https?://#', '', $website) }}
                </a>
            </td>
        </tr>
    </table>
@endif

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @foreach($closing as $line)
                @if(trim((string) $line) !== '')
                    <p style="margin:0 0 10px;font-size:14px;line-height:1.7;color:#334155;">{{ $line }}</p>
                @endif
            @endforeach

            @foreach($signers as $signer)
                @php $signer = is_array($signer) ? $signer : []; @endphp
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                    <tr>
                        <td width="40" valign="middle" style="padding:12px 0 12px 12px;font-size:18px;">👨‍💼</td>
                        <td style="padding:12px 12px 12px 4px;">
                            <p style="margin:0 0 2px;font-size:11px;font-weight:700;letter-spacing:0.04em;text-transform:uppercase;color:#94a3b8;">{{ $signer['role'] ?? 'Founder' }}</p>
                            <p style="margin:0;font-size:14px;font-weight:800;color:#0a1d37;">{{ $signer['name'] ?? '' }}</p>
                            @if(!empty($signer['org']))
                                <p style="margin:2px 0 0;font-size:13px;color:#64748b;">{{ $signer['org'] }}</p>
                            @endif
                            @if(!empty($signer['phone']))
                                <p style="margin:6px 0 0;font-size:13px;font-weight:700;color:#15803d;">📱 {{ $signer['phone'] }}</p>
                            @endif
                        </td>
                    </tr>
                </table>
            @endforeach

            @if(count($motto))
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:12px 0 0;">
                    <tr>
                        <td>
                            @foreach($motto as $line)
                                <span style="display:inline-block;margin:0 6px 6px 0;padding:7px 11px;border-radius:999px;background:#f1f5f9;color:#0a1d37;font-size:12px;font-weight:750;">{{ $line }}</span>
                            @endforeach
                        </td>
                    </tr>
                </table>
            @endif
        </td>
    </tr>
</table>
