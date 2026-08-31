@php
    $pageTitle = $title ?? 'Page';
    $pageSubtitle = $subtitle ?? null;
    $bgImage = $bgImage ?? bns_vasset('assets/images/backgrounds/page-header-bg.jpg');
    $crumbs = $breadcrumbs ?? [['label' => 'Home', 'url' => url('/')], ['label' => $pageTitle]];
@endphp

<section class="page-header page-header--bns">
    <div class="page-header__bg" style="background-image: url({{ $bgImage }});"></div>
    <div class="container">
        <div class="page-header__inner">
            <h3>{{ $pageTitle }}</h3>
            @if(!empty($pageSubtitle))
                <p class="page-header__subtitle">{{ $pageSubtitle }}</p>
            @endif
            <div class="thm-breadcrumb__inner">
                <ul class="thm-breadcrumb list-unstyled">
                    @foreach($crumbs as $crumb)
                        @if(!$loop->first)
                            <li><span class="page-header__sep" aria-hidden="true">&gt;</span></li>
                        @endif
                        <li>
                            @if(!empty($crumb['url']) && !$loop->last)
                                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                            @else
                                <span>{{ $crumb['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
