<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Membership Data Report — Business Navachar School</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 24px;
            background: #eef2f7;
            color: #0a1d37;
            font-family: Arial, Helvetica, sans-serif;
        }
        .toolbar {
            max-width: 1100px;
            margin: 0 auto 16px;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            border: none;
            border-radius: 8px;
            background: #ff5544;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-secondary {
            background: #0a2240;
        }
        .report {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }
        .report-head {
            padding: 24px;
            background: linear-gradient(135deg, #0a2240 0%, #123a5e 100%);
            color: #fff;
        }
        .report-head h1 {
            margin: 0 0 6px;
            font-size: 24px;
        }
        .report-head p {
            margin: 0;
            color: #dbe4f0;
            font-size: 13px;
        }
        .report-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            padding: 14px 24px;
            background: #fff8f5;
            border-bottom: 1px solid #ffe0d9;
            font-size: 13px;
        }
        .report-meta strong { color: #0a1d37; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border-bottom: 1px solid #eef2f7;
            padding: 10px 12px;
            text-align: left;
            vertical-align: top;
            font-size: 12px;
        }
        th {
            background: #f8fafc;
            color: #64748b;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .status {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 11px;
        }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-trustee_verified { background: #dbeafe; color: #1e40af; }
        .status-verified { background: #dcfce7; color: #166534; }
        .status-rejected { background: #fee2e2; color: #991b1b; }
        .status-refunded { background: #e0f2fe; color: #075985; }
        .empty {
            padding: 40px;
            text-align: center;
            color: #94a3b8;
        }
        .report-footer {
            padding: 16px 24px;
            color: #94a3b8;
            font-size: 12px;
            border-top: 1px solid #eef2f7;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none !important; }
            .report { border: none; border-radius: 0; box-shadow: none; }
            a { color: inherit; text-decoration: none; }
        }
    </style>
</head>
<body>
    <div class="toolbar no-print">
        <button type="button" class="btn" onclick="window.print()">Download / Print PDF</button>
        <a href="{{ route('reporting.membership', request()->query()) }}" class="btn btn-secondary">Back to Membership Data</a>
    </div>

    <div class="report">
        <div class="report-head">
            <h1>Membership Data Report</h1>
            <p>Business Navachar School (BNS) — Membership proof verification report</p>
        </div>

        <div class="report-meta">
            <div><strong>Generated:</strong> {{ $generatedAt->format('d M Y, h:i A') }} IST</div>
            <div><strong>Total Records:</strong> {{ number_format($stats['total']) }}</div>
            <div><strong>Pending Trustee:</strong> {{ number_format($stats['pending']) }}</div>
            <div><strong>Trustee Done:</strong> {{ number_format($stats['trustee_verified']) }}</div>
            <div><strong>BNS Verified:</strong> {{ number_format($stats['verified']) }}</div>
            <div><strong>Rejected:</strong> {{ number_format($stats['rejected']) }}</div>
            <div><strong>Refunded:</strong> {{ number_format($stats['refunded']) }}</div>
            @if(!empty($filters['search']))
                <div><strong>Search:</strong> {{ $filters['search'] }}</div>
            @endif
            @if(!empty($filters['status']))
                <div><strong>Status Filter:</strong> {{ $filters['status'] }}</div>
            @endif
            @if(!empty($filters['date_from']) || !empty($filters['date_to']))
                <div><strong>Date Range:</strong> {{ $filters['date_from'] ?: '—' }} to {{ $filters['date_to'] ?: '—' }}</div>
            @endif
        </div>

        @if($uploads->isEmpty())
            <div class="empty">No membership records found for the selected filters.</div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Submitted</th>
                        <th>Membership Name</th>
                        <th>Membership No</th>
                        <th>Contact</th>
                        <th>Registration No.</th>
                        <th>Overall Status</th>
                        <th>Trustee</th>
                        <th>BNS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($uploads as $index => $upload)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $upload->created_at?->format('d M Y, h:i A') ?: '—' }}</td>
                            <td>{{ $upload->membership_name }}</td>
                            <td>{{ $upload->membership_no }}</td>
                            <td>
                                {{ $upload->mobile ?: '—' }}<br>
                                {{ $upload->email ?: '—' }}
                            </td>
                            <td>{{ $upload->registration_number ?: '—' }}</td>
                            <td>
                                <span class="status status-{{ $upload->status }}">{{ $upload->statusLabel() }}</span>
                            </td>
                            <td>
                                <strong>{{ $upload->trusteeStatusLabel() }}</strong><br>
                                {{ $upload->trustee_remarks ?: '—' }}<br>
                                @if($upload->trustee_verified_at)
                                    {{ $upload->trusteeVerifier?->name ?: 'Admin' }} · {{ \Illuminate\Support\Carbon::parse($upload->trustee_verified_at)->format('d M Y, h:i A') }}
                                @endif
                            </td>
                            <td>
                                <strong>{{ $upload->bnsStatusLabel() }}</strong><br>
                                {{ $upload->bns_remarks ?: '—' }}<br>
                                @if($upload->bns_verified_at)
                                    {{ $upload->bnsVerifier?->name ?: 'Admin' }} · {{ \Illuminate\Support\Carbon::parse($upload->bns_verified_at)->format('d M Y, h:i A') }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        <div class="report-footer">
            This is a computer-generated membership report from Business Navachar School (BNS).
            Use Download / Print PDF, then choose “Save as PDF” in the print dialog.
        </div>
    </div>
</body>
</html>
