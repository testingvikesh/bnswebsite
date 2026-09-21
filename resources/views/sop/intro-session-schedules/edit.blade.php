@extends('sop.layouts.app')

@section('title', 'Intro Session Dates')
@section('page-title', 'Introduction Session Dates & Times')

@section('content')
<div class="mb-4 d-flex flex-wrap justify-content-between align-items-start gap-3">
    <p class="text-muted mb-0">
        Set the date and time for each Introduction Session. Changes apply to the admission popup, Events page, emails, attendance, and reporting.
    </p>
    <form method="POST" action="{{ route('controlpanel.intro-session-schedules.store') }}">
        @csrf
        <button type="submit" class="btn btn-outline-danger">
            <i class="bi bi-plus-lg me-1"></i> Add new session
        </button>
    </form>
</div>

@if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(!empty($upcoming))
<div class="sop-card p-3 mb-4">
    <div class="fw-semibold mb-2"><i class="bi bi-calendar-event me-1 text-danger"></i> Currently showing as upcoming</div>
    <div class="d-flex flex-wrap gap-2">
        @foreach($upcoming as $event)
            <span class="badge text-bg-primary">
                Session {{ $event['session_number'] ?? '' }}
                · {{ $event['date'] ?? '' }}
                · {{ $event['time'] ?? '' }}
            </span>
        @endforeach
    </div>
</div>
@endif

<form method="POST" action="{{ route('controlpanel.intro-session-schedules.update') }}">
    @csrf
    @method('PUT')

    <div class="sop-card p-4 mb-4">
        <h5 class="fw-bold mb-3">Active session for new admissions</h5>
        <p class="text-muted small">New Introduction Session form submissions will be assigned to this session.</p>
        <div class="row g-3 align-items-end">
            <div class="col-md-6">
                <label class="form-label">Forced / default session</label>
                <select name="forced_session_number" class="form-select">
                    <option value="">Auto (capacity rules)</option>
                    @foreach($sessions as $session)
                        <option value="{{ $session['session_number'] }}" @selected((int) $forcedSession === (int) $session['session_number'])>
                            Session {{ $session['session_number'] }}
                            @if($session['date_label']) — {{ $session['date_label'] }}@endif
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <div class="small text-muted">
                    Current default: <strong>Session {{ $defaultSession }}</strong>
                    @if($forcedSession)
                        · Forced: <strong>Session {{ $forcedSession }}</strong>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        @foreach($sessions as $index => $session)
            <div class="col-lg-6">
                <div class="sop-card p-4 h-100 {{ $session['is_past'] ? 'border-secondary' : '' }}">
                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                        <div>
                            <h5 class="fw-bold mb-1">Session {{ $session['session_number'] }}</h5>
                            <div class="small text-muted">
                                Live label:
                                <strong>{{ $session['date_label'] ?: '—' }}</strong>
                                · {{ $session['time_label'] ?: '—' }}
                            </div>
                        </div>
                        @if($session['is_past'])
                            <span class="badge text-bg-secondary">Past</span>
                        @else
                            <span class="badge text-bg-success">Upcoming</span>
                        @endif
                    </div>

                    <input type="hidden" name="sessions[{{ $index }}][session_number]" value="{{ $session['session_number'] }}">

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="sessions[{{ $index }}][title]" class="form-control"
                               value="{{ old('sessions.'.$index.'.title', $session['title']) }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date <span class="text-danger">*</span></label>
                        <input type="date" name="sessions[{{ $index }}][session_date]" class="form-control"
                               value="{{ old('sessions.'.$index.'.session_date', $session['session_date']) }}" required>
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Start time <span class="text-danger">*</span></label>
                            <input type="time" name="sessions[{{ $index }}][start_time]" class="form-control"
                                   value="{{ old('sessions.'.$index.'.start_time', $session['start_time']) }}" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">End time <span class="text-danger">*</span></label>
                            <input type="time" name="sessions[{{ $index }}][end_time]" class="form-control"
                                   value="{{ old('sessions.'.$index.'.end_time', $session['end_time']) }}" required>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4 d-flex flex-wrap gap-2">
        <button type="submit" class="btn btn-sop-primary px-4">
            <i class="bi bi-check-lg me-1"></i> Save Dates &amp; Times
        </button>
        <a href="{{ route('introduction-session.admission') }}" target="_blank" class="btn btn-outline-secondary">
            Preview admission form
        </a>
    </div>
</form>
@endsection
