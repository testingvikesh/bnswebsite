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
                    $followupNumbers = \App\Models\CrmFollowup::numbers();
                    $nextFollowup = 1;
                    foreach ($followupNumbers as $n) {
                        $probe = $assignment->followup($n);
                        $nextFollowup = $n;
                        if (! $probe || ! $probe->isDone()) {
                            break;
                        }
                    }
                    $nextFu = $assignment->followup($nextFollowup);
                    $statusOpenNo = $assignment->lastCalledFollowup()?->followup_no ?? $nextFollowup;
                    $statusOpenFu = $assignment->followup((int) $statusOpenNo) ?: $nextFu;
                @endphp
                <tr>
                    <td><strong>{{ $item->full_name ?? '—' }}</strong></td>
                    <td>
                        @include('crm.partials.contact-cell', [
                            'mobile' => $item->mobile ?? '',
                            'email' => $item->email ?? '',
                        ])
                    </td>
                    <td>{{ bns_intro_session_label((int) $assignment->session_number) }}</td>
                    <td>
                        <span class="bns-crm-badge bns-crm-badge--{{ $assignment->attendance_status }}">
                            {{ $assignment->attendance_status === 'present' ? 'Present' : 'Absent' }}
                        </span>
                    </td>
                    <td>
                        <div class="bns-crm-remark-dots">
                            @foreach($followupNumbers as $n)
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
                            @endforeach
                        </div>
                        <button
                            type="button"
                            class="bns-crm-followup-status js-crm-remark"
                            title="Open follow-up {{ $statusOpenNo }}"
                            data-name="{{ $item->full_name ?? 'Member' }}"
                            data-followup="{{ $statusOpenNo }}"
                            data-status="{{ $statusOpenFu ? $statusOpenFu->statusLabel() : 'Not saved yet' }}"
                            data-when="{{ $statusOpenFu && $statusOpenFu->called_at ? $statusOpenFu->called_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '' }}"
                            data-note="{{ $statusOpenFu && filled($statusOpenFu->note) ? $statusOpenFu->note : '' }}"
                            data-status-value="{{ $statusOpenFu?->status ?? 'pending' }}"
                            data-save-url="{{ route('crm.desk.followup', ['assignment' => $assignment->id, 'followup' => $statusOpenNo]) }}"
                        >{{ $assignment->lastCallStatusLabel() }}</button>
                    </td>
                    <td>
                        <div class="bns-crm-actions">
                            @if(($dialHref = bns_tel_href($item->mobile ?? '')) !== '')
                                <a class="bns-crm-mini-btn bns-crm-mini-btn--dial" href="{{ $dialHref }}" title="Dial {{ $item->mobile }}">
                                    <i class="fas fa-phone" aria-hidden="true"></i> Dial
                                </a>
                            @endif
                            <button
                                type="button"
                                class="bns-crm-mini-btn bns-crm-mini-btn--primary js-crm-remark"
                                title="Follow-up {{ $nextFollowup }}"
                                data-name="{{ $item->full_name ?? 'Member' }}"
                                data-followup="{{ $nextFollowup }}"
                                data-status="{{ $nextFu ? $nextFu->statusLabel() : 'Not saved yet' }}"
                                data-when="{{ $nextFu && $nextFu->called_at ? $nextFu->called_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') : '' }}"
                                data-note="{{ $nextFu && filled($nextFu->note) ? $nextFu->note : '' }}"
                                data-status-value="{{ $nextFu?->status ?? 'pending' }}"
                                data-save-url="{{ route('crm.desk.followup', ['assignment' => $assignment->id, 'followup' => $nextFollowup]) }}"
                            >Follow-up</button>
                        </div>
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
