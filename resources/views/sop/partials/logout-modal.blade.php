<div class="modal fade bns-logout-modal" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-2">
            <div class="modal-body text-center py-4 px-4">
                <div class="modal-icon mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle"
                     style="width:56px;height:56px;background:#fef3c7;color:#d97706;font-size:1.5rem;">
                    <i class="bi bi-box-arrow-right"></i>
                </div>
                <h5 class="fw-bold mb-2" id="logoutModalLabel">Logout from Admin Panel?</h5>
                <p class="text-muted mb-0">You will need to sign in again to access the dashboard and manage content.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4 pt-0 gap-2">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Stay signed in</button>
                <form action="{{ route('controlpanel.logout') }}" method="POST" class="d-inline js-logout-form" data-bns-skip-csrf="1">
                    @csrf
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="bi bi-box-arrow-right me-1"></i> Yes, Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
