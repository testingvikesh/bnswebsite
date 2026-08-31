@extends('sop.layouts.app')

@section('title', 'Edit Membership Upload')
@section('page-title', 'Edit Membership — '.$upload->membership_name)

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <a href="{{ route('controlpanel.membership-uploads.index') }}" class="btn btn-outline-secondary btn-sm">&larr; Back to List</a>
    <form action="{{ route('controlpanel.membership-uploads.destroy', $upload) }}" method="POST" onsubmit="return confirm('Delete this membership record?');">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-outline-danger btn-sm">Delete Record</button>
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

<form method="POST" action="{{ route('controlpanel.membership-uploads.update', $upload) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="sop-card p-4">
                <h6 class="fw-bold mb-3">Membership Details</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Membership Name <span class="text-danger">*</span></label>
                        <input type="text" name="membership_name" class="form-control" value="{{ old('membership_name', $upload->membership_name) }}" required maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Membership No <span class="text-danger">*</span></label>
                        <input type="text" name="membership_no" class="form-control" value="{{ old('membership_no', $upload->membership_no) }}" required maxlength="100">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $upload->email) }}" maxlength="255">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $upload->mobile) }}" maxlength="30">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Reference / Registration No</label>
                        <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number', $upload->registration_number) }}" maxlength="40">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            @foreach($statusOptions as $option)
                                <option value="{{ $option }}" @selected(old('status', $upload->status) === $option)>{{ ucwords(str_replace('_', ' ', $option)) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notes (internal)</label>
                        <textarea name="notes" class="form-control" rows="3" maxlength="2000">{{ old('notes', $upload->notes) }}</textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-save"></i> Update Details</button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="sop-card p-4">
                <h6 class="fw-bold mb-3">Membership Proof</h6>
                @if($url = $upload->photoUrl())
                    <a href="{{ $url }}" target="_blank" rel="noopener">
                        <img src="{{ $url }}" alt="Membership proof" class="img-fluid rounded border mb-3">
                    </a>
                @else
                    <p class="text-muted small">No proof uploaded.</p>
                @endif
                <label class="form-label">Replace Proof (optional)</label>
                <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/webp">
                <small class="text-muted">JPG, PNG or WEBP · Max 5 MB</small>
            </div>

            <div class="sop-card p-4 mt-4">
                <h6 class="fw-bold mb-3">Record Info</h6>
                <dl class="row small mb-0">
                    <dt class="col-5">Created</dt><dd class="col-7">{{ $upload->created_at?->format('d M Y, h:i A') }}</dd>
                    <dt class="col-5">Updated</dt><dd class="col-7">{{ $upload->updated_at?->format('d M Y, h:i A') }}</dd>
                </dl>
            </div>
        </div>
    </div>
</form>
@endsection
