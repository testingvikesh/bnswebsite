@php
    $pwaIcon = $siteFaviconUrl ?? asset('favicon.png');
@endphp
<link rel="icon" type="image/png" href="{{ $pwaIcon }}">
<link rel="shortcut icon" type="image/png" href="{{ $pwaIcon }}">
<link rel="apple-touch-icon" href="{{ $pwaIcon }}">
<link rel="manifest" href="{{ url('/manifest.webmanifest') }}">
<meta name="theme-color" content="#ff5544">
<meta name="application-name" content="BNS">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="BNS">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta property="og:image" content="{{ $pwaIcon }}">
<meta name="twitter:image" content="{{ $pwaIcon }}">
