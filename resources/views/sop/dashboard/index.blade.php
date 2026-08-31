@extends('sop.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="bns-hero">
    <div class="d-flex flex-wrap align-items-end justify-content-between gap-3">
        <div>
            <h1>Welcome back, {{ auth()->user()->name }}!</h1>
            <p>Manage BNS School website content and your account from here.</p>
        </div>
        <div class="text-white-50 small">
            <i class="bi bi-calendar3 me-1"></i>{{ now()->format('l, d M Y') }}
        </div>
    </div>
</div>

@if (auth()->user()->isSopAdmin())
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="bns-card bns-stat">
            <div class="bns-stat__icon bg-primary bg-opacity-10 text-primary">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <div class="bns-stat__value">{{ $userCount }}</div>
                <div class="bns-stat__label">Total Users</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="bns-card bns-stat">
            <div class="bns-stat__icon bg-danger bg-opacity-10 text-danger">
                <i class="bi bi-person-badge"></i>
            </div>
            <div>
                <div class="bns-stat__value">{{ $advisoryCount }}</div>
                <div class="bns-stat__label">Advisory Board</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="bns-card bns-stat">
            <div class="bns-stat__icon bg-success bg-opacity-10 text-success">
                <i class="bi bi-mortarboard"></i>
            </div>
            <div>
                <div class="bns-stat__value">{{ $facultyCount }}</div>
                <div class="bns-stat__label">Visiting Faculty</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="bns-card bns-stat">
            <div class="bns-stat__icon bg-warning bg-opacity-10 text-warning">
                <i class="bi bi-chat-quote"></i>
            </div>
            <div>
                <div class="bns-stat__value">{{ $testimonialCount }}</div>
                <div class="bns-stat__label">Testimonials</div>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        <div class="bns-card p-4">
            <h5 class="fw-bold mb-3">Quick Actions</h5>
            <div class="row g-3">
                @can('sopAdmin')
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.about-page.edit') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-info bg-opacity-10 text-info"><i class="bi bi-info-circle"></i></span>
                        <span><strong>About Us Page</strong><br><small class="text-muted">Edit mission, vision &amp; intro</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.team-page.edit') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people-fill"></i></span>
                        <span><strong>Team Page</strong><br><small class="text-muted">Meet Our Team content &amp; members</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.home-images.index') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-images"></i></span>
                        <span><strong>Home Page Images</strong><br><small class="text-muted">Update slider &amp; section photos</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.advisory-board.index') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-person-badge"></i></span>
                        <span><strong>Advisory Board</strong><br><small class="text-muted">Manage advisor profiles</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.faculty-page.edit') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-success bg-opacity-10 text-success"><i class="bi bi-mortarboard"></i></span>
                        <span><strong>Visiting Faculty Page</strong><br><small class="text-muted">Page content &amp; faculty profiles</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.visiting-faculty.index') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-success bg-opacity-10 text-success"><i class="bi bi-person-lines-fill"></i></span>
                        <span><strong>Faculty Profiles</strong><br><small class="text-muted">Add &amp; edit mentor cards</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.contact-page.edit') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-envelope"></i></span>
                        <span><strong>Contact Page</strong><br><small class="text-muted">Edit contact info &amp; form</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.admission-pages.index') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-mortarboard-fill"></i></span>
                        <span><strong>Admissions</strong><br><small class="text-muted">Pages &amp; applications</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.social-page.edit') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-share"></i></span>
                        <span><strong>Follow Us Page</strong><br><small class="text-muted">Edit social media links</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.whatsapp-page.edit') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-success bg-opacity-10 text-success"><i class="bi bi-whatsapp"></i></span>
                        <span><strong>WhatsApp Page</strong><br><small class="text-muted">Edit WhatsApp support content</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.contact-inquiries.index') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-info bg-opacity-10 text-info"><i class="bi bi-inbox"></i></span>
                        <span><strong>Contact Enquiries</strong><br><small class="text-muted">View form submissions</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.testimonials.index') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-chat-quote"></i></span>
                        <span><strong>Testimonials</strong><br><small class="text-muted">Update success stories</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.users.index') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people"></i></span>
                        <span><strong>Users</strong><br><small class="text-muted">Manage admin accounts</small></span>
                    </a>
                </div>
                @endcan
                <div class="col-sm-6">
                    <a href="{{ route('controlpanel.password.change') }}" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-secondary bg-opacity-10 text-secondary"><i class="bi bi-key"></i></span>
                        <span><strong>Change Password</strong><br><small class="text-muted">Update your login password</small></span>
                    </a>
                </div>
                <div class="col-sm-6">
                    <a href="{{ url('/') }}" target="_blank" class="bns-action-btn">
                        <span class="bns-action-btn__icon bg-info bg-opacity-10 text-info"><i class="bi bi-globe2"></i></span>
                        <span><strong>View Website</strong><br><small class="text-muted">Open public BNS site</small></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="bns-card p-4 h-100">
            <h5 class="fw-bold mb-3">Your Account</h5>
            <ul class="list-unstyled mb-0">
                <li class="mb-3 pb-3 border-bottom">
                    <div class="text-muted small">Name</div>
                    <strong>{{ auth()->user()->name }}</strong>
                </li>
                <li class="mb-3 pb-3 border-bottom">
                    <div class="text-muted small">Email</div>
                    <strong>{{ auth()->user()->email }}</strong>
                </li>
                <li class="mb-3 pb-3 border-bottom">
                    <div class="text-muted small">Role</div>
                    <strong>{{ auth()->user()->roleLabel() }}</strong>
                </li>
                <li>
                    <div class="text-muted small">Member since</div>
                    <strong>{{ auth()->user()->created_at?->format('d M Y') ?? '—' }}</strong>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
