@php
    $rows = $rows ?? collect();
    $status = $status ?? '';
    $employees = $employees ?? collect();
    $assignments = $assignments ?? collect();
    $sessionNo = (int) ($sessionNo ?? 0);
    $canAssign = $employees->isNotEmpty();
@endphp

<div class="bns-crm-table-wrap">
    <table class="bns-crm-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Reg. No.</th>
                <th>Program</th>
                <th>City</th>
                <th>Status</th>
                <th>Assign employee</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $index => $item)
                @php
                    $assignment = $assignments->get($item->id);
                @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->full_name ?: '—' }}</strong>
                    </td>
                    <td>
                        <div>{{ $item->mobile ?: '—' }}</div>
                        <div class="is-muted">{{ $item->email ?: '—' }}</div>
                    </td>
                    <td>{{ $item->registration_number ?: '—' }}</td>
                    <td>{{ $item->interested_program ?: '—' }}</td>
                    <td>{{ $item->city ?: '—' }}</td>
                    <td>
                        @php
                            $badgeLabel = $status === 'present' ? 'Present' : ($status === 'absent' ? 'Absent' : 'Payment done');
                            $badgeClass = $status === 'paid' ? 'paid' : $status;
                        @endphp
                        <span class="bns-crm-badge bns-crm-badge--{{ $badgeClass }}">{{ $badgeLabel }}</span>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('crm.assign') }}" class="bns-crm-assign">
                            @csrf
                            <input type="hidden" name="contact_inquiry_id" value="{{ $item->id }}">
                            <input type="hidden" name="session_number" value="{{ $sessionNo }}">
                            <input type="hidden" name="attendance_status" value="{{ $status === 'absent' ? 'absent' : 'present' }}">
                            <select name="crm_employee_id" required {{ $canAssign ? '' : 'disabled' }}>
                                <option value="">Select employee</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}" @selected((int) optional($assignment)->crm_employee_id === (int) $employee->id)>
                                        {{ $employee->name }}@if($employee->facility) · {{ $employee->facility }}@endif
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" {{ $canAssign ? '' : 'disabled' }}>
                                {{ $assignment ? 'Update' : 'Assign' }}
                            </button>
                        </form>
                        @if($assignment)
                            <div class="is-muted">Now: {{ $assignment->employee->name ?? '—' }}</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="bns-crm-empty">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
