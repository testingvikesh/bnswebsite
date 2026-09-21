@extends('layouts.front')

@section('title', 'Assign ( calling team)')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
@php
    $boardQuery = function (array $extra = []) use ($employeeId, $sessionNo, $scope, $search) {
        return array_filter([
            'employee' => $extra['employee'] ?? $employeeId,
            'session' => array_key_exists('session', $extra) ? $extra['session'] : $sessionNo,
            'scope' => $extra['scope'] ?? $scope,
            'q' => array_key_exists('q', $extra) ? $extra['q'] : $search,
        ], fn ($value) => $value !== '' && $value !== null && $value !== 0);
    };
@endphp
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => 'Assign ( calling team)',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => route('crm.dashboard')],
            ['label' => 'Assign ( calling team)'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @if(session('status'))
                <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
            @endif
            @error('selected')
                <div class="bns-mail-login__alert">{{ $message }}</div>
            @enderror
            @error('allocate')
                <div class="bns-mail-login__alert">{{ $message }}</div>
            @enderror
            @include('crm.partials.toolbar', ['active' => 'assign', 'isAdmin' => true])

            <div class="bns-crm-stats">
                <div class="bns-crm-stat">
                    <span>CRM Cordinate</span>
                    <strong>{{ number_format($totals['employees']) }}</strong>
                </div>
                <div class="bns-crm-stat">
                    <span>Unassigned{{ $sessionNo > 0 ? ' · S'.$sessionNo : '' }}</span>
                    <strong>{{ number_format($totals['unassigned']) }}</strong>
                </div>
                <div class="bns-crm-stat bns-crm-stat--present">
                    <span>{{ $selectedEmployee->name ?? 'Employee' }} assigned{{ $sessionNo > 0 ? ' · S'.$sessionNo : '' }}</span>
                    <strong>{{ number_format($totals['mine']) }}</strong>
                </div>
            </div>

            <p class="bns-crm-section-copy">Select one employee, tick members, then assign. Or click <strong>Allocation</strong> to divide <strong>last session</strong> registered members evenly across all employees. Allocation also runs every hour for new registrations.</p>

            @if($employees->isEmpty())
                <div class="bns-crm-list-card">
                    <p class="bns-crm-empty" style="padding:18px;">Add an employee first, then assign members.</p>
                </div>
            @else
                <h3 class="bns-crm-section-title">1. Choose employee</h3>
                <div class="bns-crm-employee-picks">
                    @foreach($employees as $employee)
                        <a href="{{ route('crm.assign.board', $boardQuery(['employee' => $employee->id])) }}" class="bns-crm-stat{{ (int) $employee->id === (int) $employeeId ? ' is-active' : '' }}">
                            <span>{{ $employee->name }}@if($employee->facility) · {{ $employee->facility }}@endif</span>
                            <strong>{{ number_format($employee->assignments_count) }}</strong>
                        </a>
                    @endforeach
                </div>

                <form method="GET" action="{{ route('crm.assign.board') }}" class="bns-crm-filters">
                    <input type="hidden" name="employee" value="{{ $employeeId }}">
                    <div>
                        <label for="crmAssignSearch">Search</label>
                        <input id="crmAssignSearch" type="search" name="q" value="{{ $search }}" placeholder="Name, mobile, email, registration number">
                    </div>
                    <div>
                        <label for="crmAssignSession">Session</label>
                        <select id="crmAssignSession" name="session">
                            <option value="">All sessions</option>
                            @foreach($allowedSessions as $no)
                                <option value="{{ $no }}" @selected($sessionNo === (int) $no)>S{{ $no }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="crmAssignScope">List</label>
                        <select id="crmAssignScope" name="scope">
                            <option value="unassigned" @selected($scope === 'unassigned')>Unassigned</option>
                            <option value="mine" @selected($scope === 'mine')>Assigned to {{ $selectedEmployee->name ?? 'employee' }}</option>
                            <option value="all" @selected($scope === 'all')>All members</option>
                        </select>
                    </div>
                    <div class="bns-crm-filters__actions">
                        <button type="submit"><i class="fas fa-filter" aria-hidden="true"></i> Apply</button>
                        @if($search !== '' || $sessionNo > 0 || $scope !== 'unassigned')
                            <a href="{{ route('crm.assign.board', $boardQuery(['q' => '', 'session' => 0, 'scope' => 'unassigned'])) }}" class="bns-crm-search__reset">Clear</a>
                        @endif
                    </div>
                </form>

                <form method="POST" action="{{ route('crm.assign.bulk') }}" id="crmAssignBulkForm">
                    @csrf
                    <input type="hidden" name="crm_employee_id" value="{{ $employeeId }}">

                    <section class="bns-crm-list-card">
                        <div class="bns-crm-list-card__head">
                            <h3>{{ $scope === 'mine' ? 'Assigned to '.$selectedEmployee->name : ($scope === 'unassigned' ? 'Unassigned members' : 'All members') }}</h3>
                            <span>{{ number_format($rows->count()) }} {{ Str::plural('member', $rows->count()) }}</span>
                        </div>
                        @if($scope !== 'mine')
                            <div class="bns-crm-bulkbar">
                                <label><input type="checkbox" id="crmAssignSelectAll"> Select all</label>
                                <button type="submit" class="bns-crm-mini-btn bns-crm-mini-btn--primary">
                                    Assign selected to {{ $selectedEmployee->name ?? 'employee' }}
                                </button>
                            </div>
                        @endif
                        <div class="bns-crm-table-wrap">
                            <table class="bns-crm-table">
                                <thead>
                                    <tr>
                                        @if($scope !== 'mine')
                                            <th></th>
                                        @endif
                                        <th>Name</th>
                                        <th>Contact</th>
                                        <th>Session</th>
                                        <th>Status</th>
                                        <th>Assigned to</th>
                                        @if($scope === 'mine')
                                            <th></th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($rows as $hit)
                                        @php($item = $hit->inquiry)
                                        <tr>
                                            @if($scope !== 'mine')
                                                <td>
                                                    <input type="checkbox" name="selected[]" value="{{ $item->id }}|{{ $hit->session_number }}|{{ $hit->status }}">
                                                </td>
                                            @endif
                                            <td><strong>{{ $item->full_name ?: '—' }}</strong></td>
                                            <td>
                                                <div>{{ $item->mobile ?: '—' }}</div>
                                                <div class="is-muted">{{ $item->email ?: '—' }}</div>
                                            </td>
                                            <td>{{ bns_intro_session_label((int) $hit->session_number) }}</td>
                                            <td>
                                                <span class="bns-crm-badge bns-crm-badge--{{ $hit->status }}">
                                                    {{ $hit->status === 'present' ? 'Present' : 'Absent' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($hit->assignment)
                                                    {{ $hit->assignment->employee->name ?? '—' }}
                                                @else
                                                    <span class="is-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            @if($scope === 'mine' && $hit->assignment)
                                                <td>
                                                    <button type="submit" form="crmUnassign{{ $hit->assignment->id }}" class="bns-crm-mini-btn">Remove</button>
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ $scope === 'mine' ? 6 : 6 }}" class="bns-crm-empty">No members in this list.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </form>

                @foreach($rows as $hit)
                    @if($scope === 'mine' && $hit->assignment)
                        <form id="crmUnassign{{ $hit->assignment->id }}" method="POST" action="{{ route('crm.assign.unassign', $hit->assignment) }}">
                            @csrf
                        </form>
                    @endif
                @endforeach
            @endif
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        var all = document.getElementById('crmAssignSelectAll');
        var form = document.getElementById('crmAssignBulkForm');
        if (!all || !form) return;
        all.addEventListener('change', function () {
            form.querySelectorAll('input[name="selected[]"]').forEach(function (box) {
                box.checked = all.checked;
            });
        });
    })();
</script>
@endpush
