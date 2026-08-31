<div class="bns-admission-ctas">
    <h3>Take the Next Step</h3>
    <div class="bns-admission-ctas__grid">
        @foreach($config['ctas'] ?? [] as $cta)
            @if(trim($cta['url'] ?? '', '/') === 'register')
                <button type="button" class="bns-admission-cta bns-admission-cta--{{ $cta['style'] ?? 'primary' }}" data-bs-toggle="modal" data-bs-target="#bnsIntroSessionModal">
                    <i class="{{ $cta['icon'] ?? 'fas fa-arrow-right' }}"></i> {{ $cta['label'] ?? 'Learn More' }}
                </button>
            @else
                <a href="{{ url($cta['url'] ?? '#') }}" class="bns-admission-cta bns-admission-cta--{{ $cta['style'] ?? 'primary' }}">
                    <i class="{{ $cta['icon'] ?? 'fas fa-arrow-right' }}"></i> {{ $cta['label'] ?? 'Learn More' }}
                </a>
            @endif
        @endforeach
    </div>
</div>
