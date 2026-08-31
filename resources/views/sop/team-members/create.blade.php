@extends('sop.layouts.app')

@section('title', 'Add Team Member')
@section('page-title', 'Add Team Member')

@section('content')
<form method="POST" action="{{ route('controlpanel.team-members.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="bns-card p-4">
        @include('sop.team-members._form', ['member' => $member, 'editing' => false])
        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-sop-primary">Save Member</button>
            <a href="{{ route('controlpanel.team-members.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </div>
</form>
@endsection
