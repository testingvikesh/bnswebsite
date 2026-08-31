{{--
  Renders any payload keys not already handled by the parent layout.
  Ensures smart/dedicated fallbacks never silently drop content.
--}}
@php
    $d = is_array($data ?? null) ? $data : [];
    $used = is_array($used ?? null) ? $used : [];
    $usedLookup = array_fill_keys($used, true);

    $skipExact = [
        'eyebrow', 'badge_label', 'headline', 'brand', 'tagline', 'hero_sub', 'headline_sub',
        'days', 'date', 'time', 'seminar_time', 'reporting', 'report_time',
        'view_url', 'view_label', 'portal_url', 'portal_label', 'pay_url', 'pay_label',
        'register_url', 'register_label', 'about_url', 'website', 'website_url',
        'bot_url', 'bot_number', 'bot_label', 'bot_title', 'bot_hint', 'bot_intro',
        'channel_url', 'channel_title', 'channel_label', 'map_url', 'map_label',
        'web_url', 'web_label', 'register', 'badge', 'motto_title',
    ];
    foreach ($skipExact as $k) {
        $usedLookup[$k] = true;
    }

    $isTitleKey = static fn (string $key): bool => str_ends_with($key, '_title')
        || str_ends_with($key, '_label')
        || str_ends_with($key, '_url')
        || str_ends_with($key, '_fa')
        || str_ends_with($key, '_tone')
        || str_ends_with($key, '_icon');

    $remainderStrings = [];
    $remainderLists = [];
    $remainderLinks = [];
    $remainderCards = [];
    $remainderSections = [];

    foreach ($d as $key => $value) {
        if (! is_string($key) || isset($usedLookup[$key]) || $isTitleKey($key)) {
            continue;
        }

        if (is_string($value)) {
            $text = trim($value);
            if ($text === '' || preg_match('/^https?:\/\//i', $text) || preg_match('/^(fas|fab|far)\s/i', $text)) {
                continue;
            }
            if (strlen($text) < 3) {
                continue;
            }
            $remainderStrings[] = $text;
            continue;
        }

        if (! is_array($value) || $value === []) {
            continue;
        }

        $first = reset($value);
        $title = is_string($d[$key.'_title'] ?? null) ? (string) $d[$key.'_title'] : null;
        if ($title === null || $title === '') {
            $title = ucwords(str_replace('_', ' ', $key));
        }

        // Nested sections: [{icon,title,items:[...]}]
        if (is_array($first) && isset($first['items']) && is_array($first['items'])) {
            $remainderSections[] = ['title' => $title, 'sections' => $value];
            continue;
        }

        // Link-card arrays: title/url/desc or label/url
        if (is_array($first) && (isset($first['url']) || isset($first['href'])) && (isset($first['title']) || isset($first['label']) || isset($first['desc']))) {
            $remainderLinks[] = ['title' => $title, 'items' => $value];
            continue;
        }

        // Nested fee / info cards: associative with scalar children (non-list)
        if (! is_array($first) && array_keys($value) !== range(0, count($value) - 1)) {
            $pairs = [];
            foreach ($value as $pk => $pv) {
                if (is_scalar($pv) && trim((string) $pv) !== '') {
                    $pairs[] = ['label' => is_string($pk) ? ucwords(str_replace('_', ' ', $pk)) : 'Info', 'value' => (string) $pv];
                } elseif (is_array($pv)) {
                    // e.g. non_member => ['label'=>..., 'amount'=>...]
                    foreach ($pv as $sk => $sv) {
                        if (is_scalar($sv) && trim((string) $sv) !== '' && ! preg_match('/^(fas|fab|far)\s/i', (string) $sv)) {
                            $pairs[] = [
                                'label' => trim(ucwords(str_replace('_', ' ', (string) $pk)).' · '.ucwords(str_replace('_', ' ', (string) $sk))),
                                'value' => (string) $sv,
                            ];
                        }
                    }
                }
            }
            if ($pairs !== []) {
                $remainderCards[] = ['title' => $title, 'pairs' => $pairs];
            }
            continue;
        }

        // Associative nested object (non_member/member style) when first child is array
        if (is_array($first) && array_keys($value) !== range(0, count($value) - 1)) {
            $pairs = [];
            foreach ($value as $pk => $pv) {
                if (! is_array($pv)) {
                    continue;
                }
                foreach ($pv as $sk => $sv) {
                    if (is_scalar($sv) && trim((string) $sv) !== '' && ! preg_match('/^(fas|fab|far)\s/i', (string) $sv)) {
                        $pairs[] = [
                            'label' => trim(ucwords(str_replace('_', ' ', (string) $pk)).' · '.ucwords(str_replace('_', ' ', (string) $sk))),
                            'value' => (string) $sv,
                        ];
                    }
                }
            }
            if ($pairs !== []) {
                $remainderCards[] = ['title' => $title, 'pairs' => $pairs];
                continue;
            }
        }

        // List of strings or {text/title/q/a/icon}
        if (is_string($first) || (is_array($first) && (
            isset($first['text']) || isset($first['title']) || isset($first['label'])
            || isset($first['q']) || isset($first['a']) || isset($first['icon']) || isset($first['desc'])
        ))) {
            $remainderLists[] = ['title' => $title, 'items' => $value];
        }
    }
@endphp

@foreach($remainderSections as $block)
    @foreach($block['sections'] as $section)
        @php
            $secTitle = (string) ($section['title'] ?? '');
            $secIcon = (string) ($section['icon'] ?? '📌');
            $secItems = is_array($section['items'] ?? null) ? $section['items'] : [];
            $secText = (string) ($section['text'] ?? $section['body'] ?? '');
        @endphp
        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
            <tr>
                <td style="padding:18px;">
                    @if($secTitle !== '')
                        <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                            {{ $secIcon }} {{ $secTitle }}
                        </h3>
                    @endif
                    @if($secText !== '')
                        <p style="margin:0 0 10px;font-size:14px;line-height:1.65;color:#475569;">{{ $secText }}</p>
                    @endif
                    @if($secItems !== [])
                        @include('emails.layouts._checklist', ['items' => $secItems])
                    @endif
                </td>
            </tr>
        </table>
    @endforeach
@endforeach

@foreach($remainderStrings as $text)
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:14px;background:#ffffff;">
        <tr>
            <td style="padding:16px;font-size:14px;line-height:1.7;color:#334155;">{{ $text }}</td>
        </tr>
    </table>
@endforeach

@foreach($remainderLists as $group)
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
        <tr>
            <td style="padding:18px;">
                <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $group['title'] }}</span>
                </h3>
                @include('emails.layouts._checklist', ['items' => $group['items']])
            </td>
        </tr>
    </table>
@endforeach

@foreach($remainderLinks as $group)
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
        <tr>
            <td style="padding:18px;">
                <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $group['title'] }}</span>
                </h3>
                @foreach($group['items'] as $link)
                    @php
                        $url = (string) ($link['url'] ?? $link['href'] ?? '');
                        $label = (string) ($link['title'] ?? $link['label'] ?? 'Open Link');
                        $desc = (string) ($link['desc'] ?? $link['meta'] ?? $link['text'] ?? '');
                        $icon = (string) ($link['icon'] ?? '🔗');
                        if (preg_match('/^(fas|fab|far)\s/i', $icon)) {
                            $icon = '🔗';
                        }
                    @endphp
                    @if($url !== '')
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                            <tr>
                                <td width="36" valign="top" style="padding:12px 0 12px 12px;font-size:16px;">{{ $icon }}</td>
                                <td style="padding:12px 12px 12px 4px;">
                                    <p style="margin:0 0 4px;font-size:14px;font-weight:800;color:#0a1d37;">{{ $label }}</p>
                                    @if($desc !== '')
                                        <p style="margin:0 0 8px;font-size:13px;line-height:1.5;color:#64748b;">{{ $desc }}</p>
                                    @endif
                                    <a href="{{ $url }}" style="display:inline-block;padding:8px 12px;border-radius:999px;background:#fd6e01;color:#ffffff;font-size:12px;font-weight:700;text-decoration:none;">
                                        {{ $link['cta'] ?? ($link['label'] ?? 'Open') }}
                                    </a>
                                </td>
                            </tr>
                        </table>
                    @endif
                @endforeach
            </td>
        </tr>
    </table>
@endforeach

@foreach($remainderCards as $card)
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
        <tr>
            <td style="padding:18px;">
                <h3 style="margin:0 0 12px;font-size:15px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $card['title'] }}</span>
                </h3>
                @foreach($card['pairs'] as $pair)
                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:0 0 8px;border:1px solid #e5e7eb;border-radius:12px;background:#f8fafc;">
                        <tr>
                            <td style="padding:12px 14px;">
                                <p style="margin:0 0 4px;font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;color:#94a3b8;">{{ $pair['label'] }}</p>
                                <p style="margin:0;font-size:14px;font-weight:700;color:#0a1d37;">{{ $pair['value'] }}</p>
                            </td>
                        </tr>
                    </table>
                @endforeach
            </td>
        </tr>
    </table>
@endforeach
