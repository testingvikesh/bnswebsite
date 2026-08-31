@php
    $user = $user ?? null;
    $currentRole = old('role', $user?->role ?? \App\Models\User::ROLE_USER);
@endphp

<div class="mb-3">
    <label for="name" class="form-label">Full name</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror"
           id="name" name="name" value="{{ old('name', $user?->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control @error('email') is-invalid @enderror"
           id="email" name="email" value="{{ old('email', $user?->email ?? '') }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="password" class="form-label">
        Password @if (!empty($editing))<span class="text-muted fw-normal">(leave blank to keep current)</span>@endif
    </label>
    <input type="password" class="form-control @error('password') is-invalid @enderror"
           id="password" name="password" {{ empty($editing) ? 'required' : '' }}>
    @error('password')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="password_confirmation" class="form-label">Confirm password</label>
    <input type="password" class="form-control"
           id="password_confirmation" name="password_confirmation" {{ empty($editing) ? 'required' : '' }}>
</div>

<div class="mb-4">
    <label for="role" class="form-label">System role</label>
    <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" required>
        <option value="user" @selected($currentRole === 'user')>User — assign SOP access per document</option>
        <option value="admin" @selected($currentRole === 'admin')>Administrator — full panel access</option>
    </select>
    @error('role')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
