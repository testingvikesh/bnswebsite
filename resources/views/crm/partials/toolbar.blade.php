@php
    $isAdmin = $isAdmin ?? false;
    $sessionNo = (int) ($sessionNo ?? 0);
    $employee = $employee ?? null;
@endphp
<div class="bns-mail-toolbar">
    <div>
        <span class="bns-message-intro__label">{{ $page['label'] ?? 'CRM Portal' }}</span>
        <h2 class="bns-mail-toolbar__title">
            @if($isAdmin)
                {{ $page['subtitle'] ?? 'Introduction session attendance' }}
            @else
                {{ $employee->name ?? 'Employee' }} desk
                @if(!empty($employee->facility))
                    · {{ $employee->facility }}
                @endif
            @endif
        </h2>
    </div>
    <div class="bns-mail-toolbar__links">
        @if($isAdmin)
            <a href="{{ route('crm.dashboard') }}" class="bns-mail-toolbar__link{{ ($active ?? '') === 'dashboard' ? ' is-active' : '' }}">
                <i class="fas fa-th-large" aria-hidden="true"></i> All Sessions
            </a>
            @foreach(bns_intro_session_allowed_numbers() as $no)
                <a href="{{ route('crm.session', $no) }}" class="bns-mail-toolbar__link{{ ($active ?? '') === 'session' && $sessionNo === $no ? ' is-active' : '' }}">
                    S{{ $no }}
                </a>
            @endforeach
            <a href="{{ route('crm.employees') }}" class="bns-mail-toolbar__link{{ ($active ?? '') === 'employees' ? ' is-active' : '' }}">
                <i class="fas fa-user-tie" aria-hidden="true"></i> CRM Cordinate
            </a>
            <a href="{{ route('crm.assign.board', $sessionNo > 0 ? ['session' => $sessionNo] : []) }}" class="bns-mail-toolbar__link{{ ($active ?? '') === 'assign' ? ' is-active' : '' }}">
                <i class="fas fa-user-plus" aria-hidden="true"></i> Assign ( calling team)
            </a>
            <form method="POST" action="{{ route('crm.allocate') }}" class="bns-crm-allocate-form">
                @csrf
                <button type="submit" class="bns-mail-toolbar__link">
                    <i class="fas fa-random" aria-hidden="true"></i> Allocation
                </button>
            </form>
            <a href="{{ route('crm.today-attendance') }}" class="bns-mail-toolbar__link{{ ($active ?? '') === 'today-attendance' ? ' is-active' : '' }}">
                <i class="fas fa-calendar-check" aria-hidden="true"></i> Today Attendance
            </a>
            <a href="{{ route('reporting.index', $sessionNo > 0 ? ['session' => $sessionNo] : []) }}" class="bns-mail-toolbar__link" target="_blank" rel="noopener">
                <i class="fas fa-chart-line" aria-hidden="true"></i> Reporting
            </a>
        @else
            <a href="{{ route('crm.desk') }}" class="bns-mail-toolbar__link{{ ($active ?? '') === 'desk' ? ' is-active' : '' }}">
                <i class="fas fa-phone" aria-hidden="true"></i> Call List
            </a>
        @endif
        <a href="{{ route('crm.payments') }}" class="bns-mail-toolbar__link{{ ($active ?? '') === 'payments' ? ' is-active' : '' }}">
            <i class="fas fa-rupee-sign" aria-hidden="true"></i> Payment Done
        </a>
        <a href="{{ route('crm.attendance-sync') }}" class="bns-mail-toolbar__link{{ ($active ?? '') === 'attendance-sync' ? ' is-active' : '' }}">
            <i class="fas fa-qrcode" aria-hidden="true"></i> Attendance Sync
        </a>
        <form method="POST" action="{{ route('crm.logout') }}">
            @csrf
            <button type="submit" class="bns-mail-toolbar__logout">
                <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Logout
            </button>
        </form>
    </div>
</div>
