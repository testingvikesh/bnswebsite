<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Report — Business Navachar School</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; padding: 24px; background: #eef2f7; color: #0a1d37; font-family: Arial, Helvetica, sans-serif; }
        .toolbar { max-width: 1100px; margin: 0 auto 16px; display: flex; justify-content: flex-end; gap: 10px; }
        .btn { display: inline-block; padding: 10px 18px; border: none; border-radius: 8px; background: #ff5544; color: #fff; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; }
        .btn-secondary { background: #0a2240; }
        .report { max-width: 1100px; margin: 0 auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
        .report-head { padding: 24px; background: linear-gradient(135deg, #0a2240 0%, #123a5e 100%); color: #fff; }
        .report-head h1 { margin: 0 0 6px; font-size: 24px; }
        .report-head p { margin: 0; color: #dbe4f0; font-size: 13px; }
        .report-meta { display: flex; flex-wrap: wrap; gap: 16px; padding: 14px 24px; background: #fff8f5; border-bottom: 1px solid #ffe0d9; font-size: 13px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #eef2f7; padding: 10px 12px; text-align: left; vertical-align: top; font-size: 12px; }
        th { background: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; }
        .empty { padding: 40px; text-align: center; color: #94a3b8; }
        .report-footer { padding: 16px 24px; color: #94a3b8; font-size: 12px; border-top: 1px solid #eef2f7; }
        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none !important; }
            .report { border: none; border-radius: 0; }
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button type="button" class="btn" onclick="window.print()">Download / Print PDF</button>
        <a href="{{ route('reporting.attendance', request()->query()) }}" class="btn btn-secondary">Back to Attendance</a>
    </div>

    <div class="report">
        <div class="report-head">
            <h1>Attendance Report</h1>
            <p>Business Navachar School (BNS) — Introduction Session attendance list</p>
        </div>

        <div class="report-meta">
            <div><strong>Generated:</strong> {{ $generatedAt->format('d M Y, h:i A') }} IST</div>
            <div><strong>Total:</strong> {{ number_format($stats['total']) }}</div>
            @foreach(($allowedSessions ?? bns_intro_session_allowed_numbers()) as $sessionNo)
                <div><strong>Session {{ $sessionNo }}:</strong> {{ number_format($stats['session_'.$sessionNo] ?? 0) }}</div>
            @endforeach
        </div>

        @if($rows->isEmpty())
            <div class="empty">No attendance records found for the selected filters.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Attended At</th>
                        <th>Name</th>
                        <th>Contact</th>
                        <th>Registration No.</th>
                        <th>Program</th>
                        <th>Session</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->attended_at ? \Illuminate\Support\Carbon::parse($row->attended_at)->format('d M Y, h:i A') : '—' }}</td>
                            <td>{{ $row->full_name ?: '—' }}</td>
                            <td>{{ $row->mobile ?: '—' }}<br>{{ $row->email ?: '—' }}</td>
                            <td>{{ $row->registration_number ?: '—' }}</td>
                            <td>{{ $row->program ?: '—' }}</td>
                            <td>{{ $row->sessionLabel() }}</td>
                            <td>{{ $row->statusLabel() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="report-footer">
            This is a computer-generated attendance report from Business Navachar School (BNS).
        </div>
    </div>
</body>
</html>
