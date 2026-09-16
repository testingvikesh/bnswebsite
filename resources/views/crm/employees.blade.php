@extends('layouts.front')

@section('title', 'CRM Cordinate')

@push('styles')
<link rel="stylesheet" href="{{ bns_vasset('assets/css/message-page.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/mail-portal.css') }}" />
<link rel="stylesheet" href="{{ bns_vasset('assets/css/crm-portal.css') }}" />
@endpush

@section('content')
<div class="bns-message-page bns-mail-portal bns-crm-portal">
    @include('partials.page-header', [
        'title' => 'CRM Cordinate',
        'bgImage' => $heroImage,
        'breadcrumbs' => [
            ['label' => 'Home', 'url' => url('/')],
            ['label' => 'CRM', 'url' => route('crm.dashboard')],
            ['label' => 'CRM Cordinate'],
        ],
    ])

    <section class="bns-message-content">
        <div class="container">
            @if(session('status'))
                <div class="bns-mail-login__alert bns-mail-login__alert--ok">{{ session('status') }}</div>
            @endif
            @include('crm.partials.toolbar', ['active' => 'employees', 'isAdmin' => true])

            <div class="bns-crm-grid-2">
                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>Add CRM Cordinate</h3>
                        <span>They login at /crm with this username</span>
                    </div>
                    <form method="POST" action="{{ route('crm.employees.store') }}" class="bns-crm-form">
                        @csrf
                        <label>Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required>
                        @error('name')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <label>Username</label>
                        <input type="text" name="username" value="{{ old('username') }}" required>
                        @error('username')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <label>Password</label>
                        <input type="password" name="password" required>
                        @error('password')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <label>Email ID</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="name@example.com">
                        @error('email')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <label>Mobile</label>
                        <input type="text" name="mobile" value="{{ old('mobile') }}">

                        <label>Facility</label>
                        <input type="text" name="facility" value="{{ old('facility') }}" list="crmFacilities" placeholder="Centre / facility name">
                        <datalist id="crmFacilities">
                            @foreach(($facilities ?? []) as $facility)
                                <option value="{{ $facility }}"></option>
                            @endforeach
                        </datalist>
                        @error('facility')<span class="bns-mail-login__error">{{ $message }}</span>@enderror

                        <button type="submit" class="bns-mail-login__submit">
                            <i class="fas fa-user-plus" aria-hidden="true"></i> Add CRM Cordinate
                        </button>
                    </form>
                </section>

                <section class="bns-crm-list-card">
                    <div class="bns-crm-list-card__head">
                        <h3>CRM Cordinate list</h3>
                        <span>{{ $employees->count() }} {{ Str::plural('person', $employees->count()) }}</span>
                    </div>
                    <div class="bns-crm-staff-list">
                        @forelse($employees as $employee)
                            @php
                                $nameParts = preg_split('/\s+/', trim((string) $employee->name)) ?: [];
                                $initials = strtoupper(mb_substr((string) ($nameParts[0] ?? 'C'), 0, 1).(isset($nameParts[1]) ? mb_substr((string) $nameParts[1], 0, 1) : ''));
                            @endphp
                            <article class="bns-crm-staff-card{{ $employee->is_active ? '' : ' is-inactive' }}">
                                <div class="bns-crm-staff-card__top">
                                    <div class="bns-crm-staff-avatar" aria-hidden="true">{{ $initials }}</div>
                                    <div class="bns-crm-staff-id">
                                        <strong>{{ $employee->name }}</strong>
                                        <span>{{ '@'.$employee->username }}</span>
                                    </div>
                                    <span class="bns-crm-badge {{ $employee->is_active ? 'bns-crm-badge--present' : 'bns-crm-badge--absent' }}">
                                        {{ $employee->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="bns-crm-staff-meta">
                                    <div>
                                        <span>Email</span>
                                        <em>{{ $employee->email ?: '—' }}</em>
                                    </div>
                                    <div>
                                        <span>Mobile</span>
                                        <em>{{ $employee->mobile ?: '—' }}</em>
                                    </div>
                                    <div>
                                        <span>Facility</span>
                                        <em>{{ $employee->facility ?: '—' }}</em>
                                    </div>
                                    <div>
                                        <span>Assigned</span>
                                        <em>{{ number_format($employee->assignments_count) }}</em>
                                    </div>
                                </div>
                                <div class="bns-crm-staff-actions">
                                    <a href="{{ route('crm.assign.board', ['employee' => $employee->id, 'scope' => 'unassigned']) }}" class="bns-crm-mini-btn bns-crm-mini-btn--primary">
                                        <i class="fas fa-user-plus" aria-hidden="true"></i> Assign
                                    </a>
                                    <form method="POST" action="{{ route('crm.employees.toggle', $employee) }}">
                                        @csrf
                                        <button type="submit" class="bns-crm-mini-btn">
                                            {{ $employee->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </div>
                                <details class="bns-crm-staff-edit">
                                    <summary>Edit contact</summary>
                                    <form method="POST" action="{{ route('crm.employees.update', $employee) }}" class="bns-crm-staff-edit__form">
                                        @csrf
                                        <input type="email" name="email" value="{{ old('email_'.$employee->id, $employee->email) }}" placeholder="Email ID">
                                        <input type="text" name="mobile" value="{{ old('mobile_'.$employee->id, $employee->mobile) }}" placeholder="Mobile">
                                        <input type="text" name="facility" value="{{ old('facility_'.$employee->id, $employee->facility) }}" list="crmFacilities" placeholder="Facility">
                                        <button type="submit" class="bns-crm-mini-btn bns-crm-mini-btn--primary">Save</button>
                                    </form>
                                </details>
                            </article>
                        @empty
                            <p class="bns-crm-empty">No CRM Cordinate yet. Add one to assign members.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </div>
    </section>
</div>
@endsection
