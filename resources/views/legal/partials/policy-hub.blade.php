<nav class="bns-legal__hub" aria-label="BNS legal pages">
    <p class="bns-legal__hub-title">BNS maintains {{ count(config('legal.policies', [])) }} comprehensive legal &amp; compliance pages for enterprise-level transparency:</p>
    <ul class="bns-legal__hub-list">
        @foreach(config('legal.policies', []) as $key => $item)
            <li>
                <a href="{{ route('legal.show', $key) }}" @if(($currentSlug ?? '') === $key) aria-current="page" @endif>
                    {{ $item['title'] }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
