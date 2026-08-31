@extends('layouts.front')

@section('title', $policy['title'])

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/legal-page.css') }}" />
@endpush

@section('content')
<div class="bns-legal-page">
    @include('partials.page-header', [
        'title' => 'Legal',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'Legal', 'url' => route('legal.index')],
            ['label' => $policy['title']],
        ],
    ])

    <section class="bns-legal">
        <div class="container">
            <nav class="bns-legal__tabs" aria-label="Legal policies">
                @foreach($policies as $key => $item)
                    <a href="{{ route('legal.show', $key) }}"
                       class="bns-legal__tab{{ $key === $slug ? ' is-active' : '' }}"
                       @if($key === $slug) aria-current="page" @endif
                       title="{{ $item['title'] }}">
                        {{ $item['title'] }}
                    </a>
                @endforeach
            </nav>

            <article class="bns-legal__card">
                <header class="bns-legal__header">
                    <h1>{{ $policy['title'] }}</h1>
                    @if(!empty($policy['subtitle']))
                        <p class="bns-legal__subtitle">{{ $policy['subtitle'] }}</p>
                    @endif
                </header>

                <div class="bns-legal__body">
                    @include($policy['partial'])
                </div>
            </article>
        </div>
    </section>
</div>
@endsection
