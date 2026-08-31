<div class="bns-admission-trust">
    <h3>{{ ($hub ?? null)?->trust_title ?? ($config['trust']['title'] ?? 'Why Trust BNS') }}</h3>
    <ul class="list-unstyled">
        @foreach(($hub ?? null)?->trust_items ?? ($config['trust']['items'] ?? []) as $item)
            <li><i class="fas fa-check-circle"></i> {!! bns_rich_text($item) !!}</li>
        @endforeach
    </ul>
</div>
