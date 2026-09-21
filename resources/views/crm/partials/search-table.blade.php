@php
    $rows = $rows ?? collect();
@endphp

<div class="bns-crm-table-wrap">
    <table class="bns-crm-table">
        <thead>
            <tr>
                <th>Session</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Reg. No.</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $hit)
                @php
                    $item = $hit->inquiry;
                @endphp
                <tr>
                    <td>
                        <a href="{{ route('crm.session', ['session' => $hit->session_number, 'q' => $search ?? '']) }}">{{ bns_intro_session_label((int) $hit->session_number) }}</a>
                    </td>
                    <td><strong>{{ $item->full_name ?: '—' }}</strong></td>
                    <td>
                        <div>{{ $item->mobile ?: '—' }}</div>
                        <div class="is-muted">{{ $item->email ?: '—' }}</div>
                    </td>
                    <td>{{ $item->registration_number ?: '—' }}</td>
                    <td>
                        <span class="bns-crm-badge bns-crm-badge--{{ $hit->status === 'paid' ? 'paid' : $hit->status }}">
                            {{ $hit->status === 'present' ? 'Present' : ($hit->status === 'absent' ? 'Absent' : 'Payment done') }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="bns-crm-empty">No matching people found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
