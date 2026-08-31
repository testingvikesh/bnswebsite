@extends('sop.layouts.app')

@section('title', 'Edit Team Member')
@section('page-title', 'Edit Team Member')

@section('content')
<form method="POST" action="{{ route('controlpanel.team-members.update', $member) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="bns-card p-4">
        @include('sop.team-members._form', ['member' => $member, 'editing' => true])
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-sop-primary">Update Member</button>
            <a href="{{ route('controlpanel.team-members.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</form>
@endsection
