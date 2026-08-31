<div class="modal fade" id="submissionDetailModal" tabindex="-1" aria-labelledby="submissionDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
            <div class="modal-header text-white border-0" style="background: linear-gradient(135deg, rgb(51, 61, 97) 0%, #1e293b 100%);">
                <div>
                    <div class="small text-white-50 text-uppercase fw-bold mb-1" id="jsSubmissionSource">Form Source</div>
                    <h5 class="modal-title fw-bold mb-0" id="submissionDetailModalLabel">Submission Details</h5>
                </div>
                @include('partials.modal-close-button')
            </div>
            <div class="modal-body p-4">
                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
                    <div>
                        <h4 class="mb-1 fw-bold" id="jsSubmissionName">—</h4>
                        <p class="text-muted small mb-0" id="jsSubmissionDate">—</p>
                    </div>
                    <span class="bns-reporting-reg" id="jsSubmissionReg">—</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="small text-uppercase fw-bold text-muted mb-2">Contact</div>
                            <div class="mb-2"><i class="bi bi-telephone me-2 text-primary"></i><span id="jsSubmissionMobile">—</span></div>
                            <div class="mb-2"><i class="bi bi-whatsapp me-2 text-success"></i><span id="jsSubmissionWhatsapp">—</span></div>
                            <div><i class="bi bi-envelope me-2 text-danger"></i><a href="#" id="jsSubmissionEmail">—</a></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 bg-light border h-100">
                            <div class="small text-uppercase fw-bold text-muted mb-2">Program</div>
                            <div class="mb-2"><strong>Program:</strong> <span id="jsSubmissionProgram">—</span></div>
                            <div class="mb-2"><strong>Category:</strong> <span id="jsSubmissionCategory">—</span></div>
                            <div><strong>Status:</strong> <span id="jsSubmissionStatus">—</span></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-3 bg-light border">
                            <div class="small text-uppercase fw-bold text-muted mb-2">Location</div>
                            <span id="jsSubmissionLocation">—</span>
                        </div>
                    </div>
                    <div class="col-12" id="jsSubmissionBusinessWrap" hidden>
                        <div class="p-3 rounded-3 bg-light border">
                            <div class="small text-uppercase fw-bold text-muted mb-2">Business Details</div>
                            <div class="row g-2 small">
                                <div class="col-md-6"><strong>Profession Category:</strong> <span id="jsSubmissionProfessionCategory">—</span></div>
                                <div class="col-md-6"><strong>Business / Company:</strong> <span id="jsSubmissionOrganization">—</span></div>
                                <div class="col-md-6"><strong>Business Category:</strong> <span id="jsSubmissionBusinessCategory">—</span></div>
                                <div class="col-12"><strong>Product / Service:</strong> <span id="jsSubmissionProductsServices">—</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12" id="jsSubmissionMessageWrap" hidden>
                        <div class="p-3 rounded-3 bg-light border">
                            <div class="small text-uppercase fw-bold text-muted mb-2">Message</div>
                            <p class="mb-0" id="jsSubmissionMessage"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="jsSubmissionFullLink" class="btn btn-danger">Open Full Page</a>
            </div>
        </div>
    </div>
</div>
