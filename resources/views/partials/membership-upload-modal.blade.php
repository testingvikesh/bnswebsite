<div class="modal fade bns-membership-upload-modal" id="bnsMembershipUploadModal" tabindex="-1" aria-labelledby="bnsMembershipUploadModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content bns-pay-now-modal">
            <div class="modal-header">
                <h5 class="modal-title" id="bnsMembershipUploadModalLabel">Upload Permanent Membership Proof</h5>
                @include('partials.modal-close-button', ['onLight' => true])
            </div>
            <form method="POST" action="{{ route('pay-now.membership-upload') }}" enctype="multipart/form-data" id="bnsMembershipUploadForm">
                @csrf
                @if(!empty($merchantTxnNo))
                    <input type="hidden" name="merchant_txn_no" value="{{ $merchantTxnNo }}">
                @endif
                <input type="hidden" name="registration_number" value="{{ old('registration_number', $registrationNumber ?? '') }}">
                <div class="modal-body">
                    <div class="bns-membership-form__row">
                        <div class="bns-membership-form__field">
                            <label class="form-label" for="membership_name">Membership Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="membership_name" name="membership_name" value="{{ old('membership_name') }}" required maxlength="255" placeholder="Full name on membership card" autocomplete="name">
                        </div>
                        <div class="bns-membership-form__field">
                            <label class="form-label" for="membership_no">Membership No <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="membership_no" name="membership_no" value="{{ old('membership_no') }}" required maxlength="100" placeholder="Membership number" autocomplete="off">
                        </div>
                    </div>

                    <div class="bns-membership-form__field mb-3">
                        <label class="form-label" for="membership_photo">Membership Photo / Proof <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="membership_photo" name="photo" accept="image/jpeg,image/png,image/webp" required>
                        <small class="text-muted">JPG, PNG or WEBP · Max 5 MB</small>
                    </div>

                    <div class="bns-membership-form__row">
                        <div class="bns-membership-form__field">
                            <label class="form-label" for="membership_email">Email (optional)</label>
                            <input type="email" class="form-control" id="membership_email" name="email" value="{{ old('email', $email ?? '') }}" maxlength="255" placeholder="Email address" autocomplete="email">
                        </div>
                        <div class="bns-membership-form__field">
                            <label class="form-label" for="membership_mobile">Mobile (optional)</label>
                            <input type="text" class="form-control" id="membership_mobile" name="mobile" value="{{ old('mobile', $mobile ?? '') }}" maxlength="30" placeholder="Mobile number" autocomplete="tel">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="thm-btn bns-pay-now__btn bns-pay-now__btn--primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
