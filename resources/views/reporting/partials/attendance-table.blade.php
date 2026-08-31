@php($rows = $rows ?? collect())

<div class="bns-reporting-scroll-hint d-none d-lg-block">
    <i class="bi bi-arrows-expand me-1"></i> Scroll horizontally to see all attendance details.
</div>

<div class="bns-reporting-table-wrap">
    <table class="table bns-reporting-table mb-0 align-middle">
        <thead>
            <tr>
                <th>Attended At</th>
                <th>Participant</th>
                <th>Registration No.</th>
                <th>Program</th>
                <th>Session</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td class="text-muted small text-nowrap">
                        {{ $row->attended_at ? \Illuminate\Support\Carbon::parse($row->attended_at)->format('d M Y') : '—' }}<br>
                        <span class="opacity-75">{{ $row->attended_at ? \Illuminate\Support\Carbon::parse($row->attended_at)->format('h:i A') : '—' }} IST</span>
                    </td>
                    <td style="min-width:220px">
                        <div class="fw-bold">{{ $row->full_name ?: '—' }}</div>
                        <div class="small">{{ $row->mobile ?: '—' }}</div>
                        <div class="small text-muted">{{ $row->email ?: '—' }}</div>
                    </td>
                    <td><span class="bns-reporting-reg">{{ $row->registration_number ?: '—' }}</span></td>
                    <td style="min-width:160px">{{ $row->program ?: '—' }}</td>
                    <td>
                        <span class="badge rounded-pill text-bg-primary">{{ $row->sessionLabel() }}</span>
                    </td>
                    <td>
                        <span class="badge rounded-pill text-bg-success">{{ $row->statusLabel() }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="bns-reporting-empty">
                        <i class="bi bi-clipboard-check"></i>
                        No attendance records found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bns-reporting-mobile-cards d-lg-none">
    @forelse($rows as $row)
        <article class="bns-reporting-mobile-card">
            <div class="d-flex justify-content-between gap-2 mb-2">
                <div>
                    <div class="fw-bold">{{ $row->full_name ?: '—' }}</div>
                    <div class="small text-muted">{{ $row->attended_at ? \Illuminate\Support\Carbon::parse($row->attended_at)->format('d M Y, h:i A') : '—' }}</div>
                </div>
                <span class="badge rounded-pill text-bg-success align-self-start">{{ $row->statusLabel() }}</span>
            </div>
            <div class="small mb-1"><strong>Reg. No:</strong> {{ $row->registration_number ?: '—' }}</div>
            <div class="small mb-1"><strong>Mobile:</strong> {{ $row->mobile ?: '—' }}</div>
            <div class="small mb-1"><strong>Program:</strong> {{ $row->program ?: '—' }}</div>
            <div class="small"><strong>Session:</strong> {{ $row->sessionLabel() }}</div>
        </article>
    @empty
        <div class="bns-reporting-empty">
            <i class="bi bi-clipboard-check"></i>
            No attendance records found.
        </div>
    @endforelse
</div>
