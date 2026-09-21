@php
    $followupStatusOptions = $followupStatusOptions ?? \App\Models\CrmFollowup::statusOptions();
@endphp
<div class="modal fade bns-crm-remark-modal" id="crmRemarkModal" tabindex="-1" aria-labelledby="crmRemarkModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <span class="bns-crm-remark-modal__eyebrow" id="crmRemarkModalFollowup">Follow-up</span>
                    <h5 class="modal-title" id="crmRemarkModalTitle">Remarks</h5>
                </div>
                @include('partials.modal-close-button', ['onLight' => true])
            </div>
            <form method="POST" id="crmRemarkForm">
                @csrf
                <div class="modal-body">
                    <p class="bns-crm-remark-modal__meta" id="crmRemarkModalMeta"></p>
                    <label class="form-label" for="crmRemarkStatus">Call result</label>
                    <select name="status" id="crmRemarkStatus" class="form-select mb-3" required>
                        @foreach($followupStatusOptions as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <label class="form-label" for="crmRemarkNote">Remark</label>
                    <textarea name="note" id="crmRemarkNote" class="form-control" rows="4" placeholder="What was discussed / next step"></textarea>
                    <p class="is-muted mt-2 mb-0" id="crmRemarkReadonly" hidden>Assign a calling-team employee first, then add remarks.</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="bns-crm-mini-btn bns-crm-mini-btn--primary" id="crmRemarkSave">Save remark</button>
                </div>
            </form>
        </div>
    </div>
</div>
