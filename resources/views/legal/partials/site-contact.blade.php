@php
    $phoneLabel = $phoneLabel ?? null;
    $emailPrefix = $emailPrefix ?? null;
    $showEmail = $showEmail ?? true;
    $showPhone = $showPhone ?? true;
    $showWhatsapp = $showWhatsapp ?? false;
@endphp
@if ($showEmail && filled($siteHeader['email'] ?? ''))
    <li>📧 @if ($emailPrefix){{ $emailPrefix }} @endif<a href="{{ $siteHeader['email_href'] }}">{{ $siteHeader['email'] }}</a></li>
@endif
@if ($showWhatsapp && filled($siteHeader['phone'] ?? ''))
    <li>📱 WhatsApp Support: <a href="{{ $siteHeader['phone_href'] }}">{{ $siteHeader['phone'] }}</a></li>
@endif
@if ($showPhone)
    @foreach($siteHeader['phones'] ?? [] as $phoneItem)
        <li>📞 @if ($phoneLabel && $loop->first){{ $phoneLabel }}: @endif<a href="{{ $phoneItem['href'] }}">{{ $phoneItem['label'] }}</a></li>
    @endforeach
@endif
