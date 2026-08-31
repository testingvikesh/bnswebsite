@extends('sop.layouts.app')

@section('title', 'Testimonials')
@section('page-title', 'Testimonials')

@section('content')
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
    <div>
        <h4 class="fw-bold mb-1">Testimonials</h4>
        <p class="text-muted mb-0">Manage profiles displayed on the testimonials page</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('testimonials') }}" target="_blank" class="btn btn-outline-secondary">
            <i class="bi bi-box-arrow-up-right me-1"></i> View Page
        </a>
        <a href="{{ route('controlpanel.testimonials.create') }}" class="btn btn-sop-primary">
            <i class="bi bi-plus-lg me-1"></i> Add Testimonial
        </a>
    </div>
</div>

<div class="sop-card mb-3">
    <div class="p-3 border-bottom">
        <form method="GET" action="{{ route('controlpanel.testimonials.index') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" placeholder="Search name, organization, location..."
                       value="{{ $search }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-search me-1"></i> Search</button>
            </div>
            @if ($search->isNotEmpty())
                <div class="col-auto"><a href="{{ route('controlpanel.testimonials.index') }}" class="btn btn-link text-decoration-none">Clear</a></div>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th style="width: 56px;">#</th>
                    <th style="width: 64px;">Photo</th>
                    <th>Name</th>
                    <th>Organization</th>
                    <th>Location</th>
                    <th style="width: 72px;">Order</th>
                    <th style="width: 88px;">Status</th>
                    <th class="text-end" style="width: 96px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($testimonials as $item)
                    <tr>
                        <td class="text-muted">{{ $testimonials->firstItem() + $loop->index }}</td>
                        <td>
                            @if($item->photo_url)
                                <img src="{{ $item->photo_url }}" alt="" class="rounded" style="width:52px;height:52px;object-fit:cover;">
                            @else
                                <div class="rounded d-flex align-items-center justify-content-center text-white"
                                     style="width:52px;height:52px;background:linear-gradient(135deg,#0a2240,#123a5e);">
                                    <i class="bi bi-person"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong class="d-block">{{ $item->full_name }}</strong>
                            <span class="text-muted small">{{ $item->designation }}{{ $item->organization ? ' — '.$item->organization : '' }}</span>
                        </td>
                        <td class="small">{{ $item->organization ?: '—' }}</td>
                        <td class="small">{{ $item->location ?: '—' }}</td>
                        <td><span class="badge text-bg-light text-dark border">{{ $item->sort_order }}</span></td>
                        <td>
                            @if($item->is_active)
                                <span class="badge text-bg-success">Active</span>
                            @else
                                <span class="badge text-bg-secondary">Hidden</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('controlpanel.testimonials.edit', $item) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
                            <button type="button" class="btn btn-sm btn-outline-danger"
                                    data-bs-toggle="modal" data-bs-target="#deleteTestimonialModal"
                                    data-name="{{ $item->full_name }}"
                                    data-delete-url="{{ route('controlpanel.testimonials.destroy', $item) }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-5">
                            No testimonials yet.
                            <a href="{{ route('controlpanel.testimonials.create') }}">Add the first one</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($testimonials->hasPages())
        <div class="p-3 border-top">{{ $testimonials->links() }}</div>
    @endif
</div>

<div class="modal fade" id="deleteTestimonialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title"><i class="bi bi-exclamation-circle text-danger me-2"></i>Delete Testimonial</h5>
                @include('partials.modal-close-button', ['onLight' => true])
            </div>
            <div class="modal-body">Remove <strong id="deleteTestimonialName"></strong>? This cannot be undone.</div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteTestimonialForm" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('deleteTestimonialModal')?.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        document.getElementById('deleteTestimonialName').textContent = button.getAttribute('data-name');
        document.getElementById('deleteTestimonialForm').action = button.getAttribute('data-delete-url');
    });
</script>
@endpush
