@php
    $mobile = trim((string) ($mobile ?? ''));
    $email = trim((string) ($email ?? ''));
    $tel = bns_tel_href($mobile);
@endphp
@if($tel !== '')
    <a class="bns-crm-dial" href="{{ $tel }}" title="Call {{ $mobile }}">
        <i class="fas fa-phone" aria-hidden="true"></i>
        <span>{{ $mobile !== '' ? $mobile : 'Call' }}</span>
    </a>
@elseif($mobile !== '')
    <div>{{ $mobile }}</div>
@else
    <div>—</div>
@endif
@if($email !== '')
    <div class="is-muted">
        <a class="bns-crm-mail" href="mailto:{{ $email }}">{{ $email }}</a>
    </div>
@else
    <div class="is-muted">—</div>
@endif
