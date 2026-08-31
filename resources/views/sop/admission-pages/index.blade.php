@extends('sop.layouts.app')

@section('title', 'Admission Pages')
@section('page-title', 'Admission Pages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <p class="text-muted mb-0">Manage admission section pages. Hub: <a href="{{ route('controlpanel.admission-hub.edit') }}">Admissions Hub</a>. Applications: <a href="{{ route('controlpanel.admission-forms.index') }}">Admission Forms</a></p>
    <a href="{{ route('admissions.index') }}" target="_blank" class="btn btn-outline-secondary btn-sm">Preview Hub</a>
</div>

<div class="sop-card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Page</th><th>Slug</th><th>Status</th><th></th></tr></thead>
            <tbody>
                <tr>
                    <td><strong>Apply Now</strong></td>
                    <td>apply-now</td>
                    <td><span class="badge text-bg-success">Active</span></td>
                    <td class="text-end"><a href="{{ route('register') }}" target="_blank" class="btn btn-sm btn-outline-primary">View</a></td>
                </tr>
                @foreach($pages as $page)
                <tr>
                    <td>{{ $page->page_title }}</td>
                    <td><code>{{ $page->slug }}</code></td>
                    <td><span class="badge {{ $page->is_active ? 'text-bg-success' : 'text-bg-secondary' }}">{{ $page->is_active ? 'Active' : 'Hidden' }}</span></td>
                    <td class="text-end">
                        @if($page->exists)
                            <a href="{{ route('controlpanel.admission-pages.edit', $page) }}" class="btn btn-sm btn-primary">Edit</a>
                            <a href="{{ route('admissions.page', $page->slug) }}" target="_blank" class="btn btn-sm btn-outline-secondary">View</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
