{{-- Dual-tone session cards (matches web evening/morning / green/blue) --}}
@php
    $sessions = is_array($sessions ?? null) ? $sessions : [];
    $sessionsTitle = (string) ($sessionsTitle ?? 'Choose Your Preferred Session');
@endphp
@if($sessions !== [])
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin:14px 0 0;border:1px solid #e5e7eb;border-radius:16px;background:#ffffff;">
    <tr>
        <td style="padding:18px;">
            @if($sessionsTitle !== '')
                <h3 style="margin:0 0 14px;font-size:16px;font-weight:800;color:#0a1d37;">
                    <span style="display:inline-block;padding-bottom:6px;border-bottom:2px solid rgba(253,110,1,0.45);">{{ $sessionsTitle }}</span>
                </h3>
            @endif
            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    @foreach($sessions as $index => $session)
                        @php
                            $tone = strtolower((string) ($session['tone'] ?? ($index === 0 ? 'evening' : 'morning')));
                            $isBlue = in_array($tone, ['morning', 'blue', 'session-2', 'session2'], true);
                            $isGreen = in_array($tone, ['green'], true);
                            if (! $isBlue && ! $isGreen && $index === 1) {
                                $isBlue = true;
                            }
                            if ($isGreen) {
                                $border = '#bbf7d0';
                                $bg = '#f0fdf4';
                                $chipBg = '#dcfce7';
                                $chipColor = '#15803d';
                                $iconColor = '#16a34a';
                            } elseif ($isBlue) {
                                $border = '#bfdbfe';
                                $bg = '#eff6ff';
                                $chipBg = '#dbeafe';
                                $chipColor = '#1d4ed8';
                                $iconColor = '#2563eb';
                            } else {
                                $border = '#fed7aa';
                                $bg = '#fff7ed';
                                $chipBg = '#ffedd5';
                                $chipColor = '#c2410c';
                                $iconColor = '#ea580c';
                            }
                            $pad = $index === 0 ? '0 6px 0 0' : '0 0 0 6px';
                            if (count($sessions) === 1) {
                                $pad = '0';
                            }
                        @endphp
                        <td width="{{ count($sessions) > 1 ? '50%' : '100%' }}" valign="top" style="padding:{{ $pad }};">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border:1px solid {{ $border }};border-radius:14px;background:{{ $bg }};">
                                <tr>
                                    <td style="padding:14px;">
                                        <p style="margin:0 0 10px;display:inline-block;padding:5px 10px;border-radius:999px;background:{{ $chipBg }};color:{{ $chipColor }};font-size:11px;font-weight:800;letter-spacing:0.06em;text-transform:uppercase;">
                                            {{ $session['label'] ?? ('Session '.($index + 1)) }}
                                        </p>
                                        @if(!empty($session['date']))
                                            <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#0a1d37;">📅 {{ $session['date'] }}</p>
                                        @endif
                                        @if(!empty($session['day']))
                                            <p style="margin:0 0 6px;font-size:13px;font-weight:700;color:#0a1d37;">☀ {{ $session['day'] }}</p>
                                        @endif
                                        @if(!empty($session['time']))
                                            <p style="margin:0 0 6px;font-size:13px;font-weight:800;color:#0a1d37;">🕘 {{ $session['time'] }}</p>
                                        @endif
                                        @if(!empty($session['reporting']))
                                            <p style="margin:0;font-size:12px;font-weight:700;color:{{ $iconColor }};">⏰ Report: {{ $session['reporting'] }}</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </td>
                        @if($index === 0 && count($sessions) > 2)
                            </tr><tr>
                        @endif
                    @endforeach
                </tr>
            </table>
        </td>
    </tr>
</table>
@endif
