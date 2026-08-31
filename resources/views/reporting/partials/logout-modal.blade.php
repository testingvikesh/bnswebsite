<div class="modal fade bns-reporting-logout-modal" id="reportingLogoutModal" tabindex="-1" aria-labelledby="reportingLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4 px-4">
                <div class="modal-icon">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <h5 class="fw-bold mb-2" id="reportingLogoutModalLabel">Logout from Reporting?</h5>
                <p class="text-muted mb-0">You will need to sign in again with your username and password to view contact form submissions.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 pt-0 gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Stay signed in</button>
                <form action="{{ route('reporting.logout') }}" method="POST" class="d-inline js-logout-form" data-bns-skip-csrf="1">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-box-arrow-right me-1"></i> Yes, Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
