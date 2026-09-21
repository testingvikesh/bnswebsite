<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <style>
        body, table, td, th {
            font-family: Calibri, Arial, Helvetica, sans-serif;
            font-size: 11pt;
        }
        .brand {
            background: #0a1d37;
            color: #ffffff;
            font-size: 20pt;
            font-weight: 800;
            padding: 14px 12px;
        }
        .subtitle {
            background: #ff5544;
            color: #ffffff;
            font-size: 13pt;
            font-weight: 700;
            padding: 10px 12px;
        }
        .meta {
            background: #fff4f0;
            color: #0a1d37;
            font-size: 10pt;
            padding: 8px 12px;
        }
        .stat-label {
            background: #123a5e;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
            padding: 8px;
        }
        .stat-value {
            background: #e8f0fe;
            color: #0a1d37;
            font-weight: 800;
            text-align: center;
            font-size: 14pt;
            padding: 10px;
        }
        .head {
            background: #0a1d37;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
            border: 1px solid #08203a;
            padding: 8px 6px;
        }
        .n { mso-number-format:"\@"; }
        .amt { mso-number-format:"\#\,\#\#0\.00"; font-weight: 700; color: #047857; }
        .paid {
            background: #16a34a;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
        }
        .unpaid {
            background: #f97316;
            color: #ffffff;
            font-weight: 700;
            text-align: center;
        }
        .row-even { background: #f8fafc; }
        .row-odd { background: #ffffff; }
        .row-paid { background: #ecfdf5; }
        .row-unpaid { background: #fff7ed; }
        .session {
            background: #dbeafe;
            color: #1e3a8a;
            font-weight: 700;
            text-align: center;
        }
        td {
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            vertical-align: middle;
        }
    </style>
</head>
<body>
@php
    $isPaid = $isPaid ?? false;
    $generatedAt = $generatedAt ?? now('Asia/Kolkata');
    $stats = $stats ?? ['registered' => 0, 'paid' => 0, 'filtered' => 0];
    $eventDate = is_array($event ?? null) ? trim((string) (($event['date'] ?? '').' '.($event['time'] ?? ''))) : '';
@endphp
<table border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="brand" colspan="{{ $isPaid ? 11 : 12 }}">Business Navachar School (BNS)</td>
    </tr>
    <tr>
        <td class="subtitle" colspan="{{ $isPaid ? 11 : 12 }}">{{ $title }} · {{ $sessionTitle }}</td>
    </tr>
    <tr>
        <td class="meta" colspan="{{ $isPaid ? 11 : 12 }}">
            Generated: {{ $generatedAt->format('d M Y, h:i A') }} (IST)
            @if($eventDate !== '')
                · {{ $eventDate }}
            @endif
            @if(($search ?? '') !== '')
                · Search: {{ $search }}
            @endif
        </td>
    </tr>
    <tr>
        <td class="stat-label">Registered</td>
        <td class="stat-label">Payment Done</td>
        <td class="stat-label">Showing</td>
        <td class="stat-label" colspan="{{ $isPaid ? 8 : 9 }}">Report</td>
    </tr>
    <tr>
        <td class="stat-value">{{ number_format($stats['registered'] ?? 0) }}</td>
        <td class="stat-value">{{ number_format($stats['paid'] ?? 0) }}</td>
        <td class="stat-value">{{ number_format($stats['filtered'] ?? 0) }}</td>
        <td class="meta" colspan="{{ $isPaid ? 8 : 9 }}">{{ $isPaid ? 'Successful payment list' : 'Unique registered members with payment status' }}</td>
    </tr>
    <tr>
        <th class="head">Sr. No.</th>
        <th class="head">Session</th>
        @if($isPaid)
            <th class="head">Paid Date</th>
            <th class="head">Name</th>
            <th class="head">Mobile</th>
            <th class="head">Email</th>
            <th class="head">Reg. No.</th>
            <th class="head">Amount</th>
            <th class="head">Payment Mode</th>
            <th class="head">Txn No.</th>
            <th class="head">Program</th>
        @else
            <th class="head">Registered At</th>
            <th class="head">Name</th>
            <th class="head">Mobile</th>
            <th class="head">Email</th>
            <th class="head">Reg. No.</th>
            <th class="head">Form Source</th>
            <th class="head">Program</th>
            <th class="head">Payment</th>
            <th class="head">Amount</th>
            <th class="head">Paid Date</th>
        @endif
    </tr>
    @foreach($rows as $index => $row)
        @php
            $inquiry = $row['inquiry'] ?? null;
            $payment = $row['payment'] ?? null;
            $sessionNo = (int) ($row['session'] ?? 0);
            $paid = (bool) $payment;
            $rowClass = $paid ? 'row-paid' : 'row-unpaid';
            if ($isPaid) {
                $rowClass = $index % 2 === 0 ? 'row-paid' : 'row-even';
            }
        @endphp
        <tr>
            <td class="{{ $rowClass }}" align="center">{{ $index + 1 }}</td>
            <td class="session">{{ $sessionNo > 0 ? 'Session '.$sessionNo : '—' }}</td>
            @if($isPaid)
                <td class="{{ $rowClass }}">{{ $payment?->paid_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—' }}</td>
                <td class="{{ $rowClass }}"><b>{{ $payment->customer_name ?? ($inquiry->full_name ?? '—') }}</b></td>
                <td class="{{ $rowClass }} n">{{ $payment->customer_mobile ?? ($inquiry->mobile ?? '—') }}</td>
                <td class="{{ $rowClass }}">{{ $payment->customer_email ?? ($inquiry->email ?? '—') }}</td>
                <td class="{{ $rowClass }} n">{{ $payment->registration_number ?? ($inquiry->registration_number ?? '—') }}</td>
                <td class="{{ $rowClass }} amt">{{ $payment ? number_format((float) $payment->amount, 2) : '' }}</td>
                <td class="{{ $rowClass }}">{{ $payment->payment_mode ?? '—' }}</td>
                <td class="{{ $rowClass }} n">{{ $payment->merchant_txn_no ?? '—' }}</td>
                <td class="{{ $rowClass }}">{{ $inquiry?->interested_program ?? '—' }}</td>
            @else
                <td class="{{ $rowClass }}">{{ $inquiry?->created_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '—' }}</td>
                <td class="{{ $rowClass }}"><b>{{ $inquiry->full_name ?? '—' }}</b></td>
                <td class="{{ $rowClass }} n">{{ $inquiry->mobile ?? '—' }}</td>
                <td class="{{ $rowClass }}">{{ $inquiry->email ?? '—' }}</td>
                <td class="{{ $rowClass }} n">{{ $inquiry->registration_number ?? '—' }}</td>
                <td class="{{ $rowClass }}">{{ $inquiry?->formSourceLabel() ?? '—' }}</td>
                <td class="{{ $rowClass }}">{{ $inquiry?->interested_program ?? '—' }}</td>
                <td class="{{ $paid ? 'paid' : 'unpaid' }}">{{ $paid ? 'Payment done' : 'Not paid' }}</td>
                <td class="{{ $rowClass }} amt">{{ $paid ? number_format((float) $payment->amount, 2) : '' }}</td>
                <td class="{{ $rowClass }}">{{ $payment?->paid_at?->timezone('Asia/Kolkata')->format('d M Y, h:i A') ?: '' }}</td>
            @endif
        </tr>
    @endforeach
    @if($rows->isEmpty())
        <tr>
            <td class="row-even" colspan="{{ $isPaid ? 11 : 12 }}" align="center">No records found.</td>
        </tr>
    @endif
</table>
</body>
</html>
