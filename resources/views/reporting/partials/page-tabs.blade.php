@php
    $activeTab = $activeTab ?? 'registration';
    $showAttendanceTab = bns_attendance_enabled();
    $tabCount = $showAttendanceTab ? 4 : 3;
@endphp

<nav class="bns-reporting-page-tabs bns-reporting-page-tabs--cols-{{ $tabCount }} mb-4" aria-label="Reporting pages">
    <a
        href="{{ route('reporting.index') }}"
        class="bns-reporting-page-tab{{ $activeTab === 'registration' ? ' is-active' : '' }}"
        @if($activeTab === 'registration') aria-current="page" @endif
    >
        <i class="bi bi-people-fill"></i>
        <span>
            <strong>Registration Report</strong>
            <small>Sessions and enquiries</small>
        </span>
    </a>
    <a
        href="{{ route('reporting.payments') }}"
        class="bns-reporting-page-tab{{ $activeTab === 'payments' ? ' is-active' : '' }}"
        @if($activeTab === 'payments') aria-current="page" @endif
    >
        <i class="bi bi-check-circle-fill"></i>
        <span>
            <strong>Admission Confirm</strong>
            <small>Successful payments</small>
        </span>
    </a>
    <a
        href="{{ route('reporting.membership') }}"
        class="bns-reporting-page-tab{{ $activeTab === 'membership' ? ' is-active' : '' }}"
        @if($activeTab === 'membership') aria-current="page" @endif
    >
        <i class="bi bi-person-vcard-fill"></i>
        <span>
            <strong>Membership Data</strong>
            <small>Proof & verification</small>
        </span>
    </a>
    @if($showAttendanceTab)
        <a
            href="{{ route('reporting.attendance') }}"
            class="bns-reporting-page-tab{{ $activeTab === 'attendance' ? ' is-active' : '' }}"
            @if($activeTab === 'attendance') aria-current="page" @endif
        >
            <i class="bi bi-clipboard-check-fill"></i>
            <span>
                <strong>Attendance</strong>
                <small>Session check-in list</small>
            </span>
        </a>
    @endif
</nav>
