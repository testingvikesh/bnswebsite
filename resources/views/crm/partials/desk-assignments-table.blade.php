@php
    $assignments = $assignments ?? collect();
@endphp
<div class="bns-crm-table-wrap">
    <table class="bns-crm-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Contact</th>
                <th>Session</th>
                <th>Attendance</th>
                <th>Follow-ups</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($assignments as $assignment)
                @php
                    $item = $assignment->inquiry;
                    $nextFollowup = 1;
                    for ($n = 1; $n <= 3; $n++) {
                        $probe = $assignment->followup($n);
                        $nextFollowup = $n;
                        if (! $probe || ! $probe->isDone()) {
                            break;
                        }
                    }
                    $nextFu = $assignment->followup($nextFollowup);
                @endphp
                <tr>
                    <td><strong>{{ $item->full_name ?? '—' }}</strong></td>
                    <td>
                        <div>{{ $item->mobile ?? '—' }}</div>
                        <div class="is-muted">{{ $item->email ?? '—' }}</div>
                    </td>
                    <td>{{ bns_intro_session_label((int) $assignment->session_number) }}</td>
                    <td>
                        <span class="bns-crm-badge bns-crm-badge--{{ $assignment->attendance_status }}">
                            {{ $assignment->attendance_status === 'present' ? 'Present' : 'Absent' }}
                        </span>
                    </td>
                    <td>
                        @for($n = 1; $n <= 3; $n++)
                            @php($fu = $assignment->followup($n))
                            <button
                                type="button"
                                class="bns-crm-dot {{ $fu && $fu->isDone() ? 'is-done' : '' }} js-crm-remark"
                                title="View follow-up {{ $n }} remark"
                                data-name="{{ $item->full_name ?? 'Member' }}"
                                data-followup="{{ $n }}"
                                data-status="{{ $fu ? $fu->statusLabel() : 'Not saved yet' }}"
                                data-when="{{ $fu && $fu->called_at ? $fu->called_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '' }}"
                                data-note="{{ $fu && filled($fu->note) ? $fu->note : '' }}"
                                data-status-value="{{ $fu?->status ?? 'pending' }}"
                                data-save-url="{{ route('crm.desk.followup', ['assignment' => $assignment->id, 'followup' => $n]) }}"
                            >{{ $n }}</button>
                        @endfor
                        <div class="is-muted">{{ $assignment->lastCallStatusLabel() }}</div>
                    </td>
                    <td>
                        <button
                            type="button"
                            class="bns-crm-mini-btn bns-crm-mini-btn--primary js-crm-remark"
                            title="Call / Follow-up {{ $nextFollowup }}"
                            data-name="{{ $item->full_name ?? 'Member' }}"
                            data-followup="{{ $nextFollowup }}"
                            data-status="{{ $nextFu ? $nextFu->statusLabel() : 'Not saved yet' }}"
                            data-when="{{ $nextFu && $nextFu->called_at ? $nextFu->called_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '' }}"
                            data-note="{{ $nextFu && filled($nextFu->note) ? $nextFu->note : '' }}"
                            data-status-value="{{ $nextFu?->status ?? 'pending' }}"
                            data-save-url="{{ route('crm.desk.followup', ['assignment' => $assignment->id, 'followup' => $nextFollowup]) }}"
                        >Call / Follow-up</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="bns-crm-empty">No members in this list. Paid members are under Payment Done.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
