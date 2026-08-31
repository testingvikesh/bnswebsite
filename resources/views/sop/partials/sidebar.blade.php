<aside class="bns-sidebar" id="bnsSidebar">
    <div class="bns-sidebar__brand">
        <img src="{{ $siteLogoUrl }}" alt="{{ $siteLogoAlt }}">
        <div>
            <strong>Admin Panel</strong>
            <small>BNS School</small>
        </div>
    </div>

    <nav class="bns-sidebar__nav">
        <div class="bns-sidebar__label">Main</div>
        <a href="{{ route('controlpanel.dashboard') }}" class="{{ request()->routeIs('controlpanel.dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        @can('sopAdmin')
        <div class="bns-sidebar__label mt-2">Website Content</div>
        <a href="{{ route('controlpanel.about-page.edit') }}" class="{{ request()->routeIs('controlpanel.about-page.*') ? 'active' : '' }}">
            <i class="bi bi-info-circle"></i> About Us Page
        </a>
        <a href="{{ route('controlpanel.team-page.edit') }}" class="{{ request()->routeIs('controlpanel.team-page.*') || request()->routeIs('controlpanel.team-members.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Team Page
        </a>
        <a href="{{ route('controlpanel.home-images.index') }}" class="{{ request()->routeIs('controlpanel.home-images.*') ? 'active' : '' }}">
            <i class="bi bi-images"></i> Home Page Images
        </a>
        <a href="{{ route('controlpanel.home-reels.index') }}" class="{{ request()->routeIs('controlpanel.home-reels.*') ? 'active' : '' }}">
            <i class="bi bi-camera-reels"></i> Home Page Reels
        </a>
        <a href="{{ route('controlpanel.event-galleries.index') }}" class="{{ request()->routeIs('controlpanel.event-galleries.*') ? 'active' : '' }}">
            <i class="bi bi-images"></i> Event Galleries
        </a>
        <a href="{{ route('controlpanel.sponsor-members.index') }}" class="{{ request()->routeIs('controlpanel.sponsor-members.*') ? 'active' : '' }}">
            <i class="bi bi-hand-thumbs-up"></i> Sponsors Page
        </a>
        <a href="{{ route('controlpanel.site-branding.edit') }}" class="{{ request()->routeIs('controlpanel.site-branding.*') ? 'active' : '' }}">
            <i class="bi bi-sliders"></i> Site Settings
        </a>
        <a href="{{ route('controlpanel.advisory-board.index') }}" class="{{ request()->routeIs('controlpanel.advisory-board.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Advisory Board
        </a>
        <a href="{{ route('controlpanel.contact-page.edit') }}" class="{{ request()->routeIs('controlpanel.contact-page.*') ? 'active' : '' }}">
            <i class="bi bi-envelope"></i> Contact Page
        </a>
        <a href="{{ route('controlpanel.contact-inquiries.index') }}" class="{{ request()->routeIs('controlpanel.contact-inquiries.*') ? 'active' : '' }}">
            <i class="bi bi-inbox"></i> Contact Enquiries
        </a>
        <a href="{{ route('controlpanel.whatsapp-page.edit') }}" class="{{ request()->routeIs('controlpanel.whatsapp-page.*') ? 'active' : '' }}">
            <i class="bi bi-whatsapp"></i> WhatsApp Page
        </a>
        <a href="{{ route('controlpanel.social-page.edit') }}" class="{{ request()->routeIs('controlpanel.social-page.*') ? 'active' : '' }}">
            <i class="bi bi-share"></i> Follow Us Page
        </a>
        <a href="{{ route('controlpanel.admission-hub.edit') }}" class="{{ request()->routeIs('controlpanel.admission-hub.*') ? 'active' : '' }}">
            <i class="bi bi-mortarboard-fill"></i> Admissions Hub
        </a>
        <a href="{{ route('controlpanel.admission-pages.index') }}" class="{{ request()->routeIs('controlpanel.admission-pages.*') ? 'active' : '' }}">
            <i class="bi bi-journal-text"></i> Admission Sections
        </a>
        <a href="{{ route('controlpanel.admission-forms.index') }}" class="{{ request()->routeIs('controlpanel.admission-forms.*') || request()->routeIs('controlpanel.admission-applications.*') ? 'active' : '' }}">
            <i class="bi bi-inbox-fill"></i> Admission Forms
        </a>
        <a href="{{ route('controlpanel.payments.index') }}" class="{{ request()->routeIs('controlpanel.payments.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card"></i> Payment Reports
        </a>
        <a href="{{ route('controlpanel.membership-uploads.index') }}" class="{{ request()->routeIs('controlpanel.membership-uploads.*') ? 'active' : '' }}">
            <i class="bi bi-person-vcard"></i> Membership Uploads
        </a>
        <a href="{{ route('controlpanel.intro-session-emails.index') }}" class="{{ request()->routeIs('controlpanel.intro-session-emails.*') ? 'active' : '' }}">
            <i class="bi bi-envelope-paper"></i> Session Email Sending
        </a>
        <a href="{{ route('controlpanel.attendance.index') }}" class="{{ request()->routeIs('controlpanel.attendance.*') ? 'active' : '' }}">
            <i class="bi bi-qr-code-scan"></i> Attendance Module
        </a>

        <a href="{{ route('controlpanel.faculty-page.edit') }}" class="{{ request()->routeIs('controlpanel.faculty-page.*') || request()->routeIs('controlpanel.visiting-faculty.*') ? 'active' : '' }}">
            <i class="bi bi-mortarboard"></i> Visiting Faculty
        </a>
        <a href="{{ route('controlpanel.testimonials.index') }}" class="{{ request()->routeIs('controlpanel.testimonials.*') ? 'active' : '' }}">
            <i class="bi bi-chat-quote"></i> Testimonials
        </a>

        <div class="bns-sidebar__label mt-2">Settings</div>
        <a href="{{ route('controlpanel.users.index') }}" class="{{ request()->routeIs('controlpanel.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Users
        </a>
        @endcan

        <a href="{{ route('controlpanel.password.change') }}" class="{{ request()->routeIs('controlpanel.password.change*') ? 'active' : '' }}">
            <i class="bi bi-key"></i> Change Password
        </a>
        <a href="{{ url('/') }}" target="_blank">
            <i class="bi bi-box-arrow-up-right"></i> View Website
        </a>
    </nav>

    <div class="bns-sidebar__footer">
        <button type="button" class="btn btn-outline-light btn-sm w-100" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
        </button>
    </div>
</aside>
