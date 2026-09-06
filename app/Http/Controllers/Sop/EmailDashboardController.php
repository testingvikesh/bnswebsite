<?php

namespace App\Http\Controllers\Sop;

use App\Http\Controllers\Controller;
use App\Models\OutboundEmailLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class EmailDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $filters = [
            'q' => trim((string) $request->query('q', '')),
            'process' => trim((string) $request->query('process', '')),
            'status' => trim((string) $request->query('status', '')),
            'sender' => trim((string) $request->query('sender', '')),
            'date_from' => trim((string) $request->query('date_from', '')),
            'date_to' => trim((string) $request->query('date_to', '')),
        ];

        $logs = collect();
        $senders = collect();
        $stats = ['total' => 0, 'sent' => 0, 'failed' => 0];

        if (Schema::hasTable('outbound_email_logs')) {
            $query = OutboundEmailLog::query()->latest('sent_at')->latest('id');

            if ($filters['q'] !== '') {
                $search = $filters['q'];
                $query->where(function ($builder) use ($search) {
                    $builder
                        ->where('to_email', 'like', "%{$search}%")
                        ->orWhere('subject', 'like', "%{$search}%")
                        ->orWhere('body_text', 'like', "%{$search}%")
                        ->orWhere('body_html', 'like', "%{$search}%")
                        ->orWhere('from_email', 'like', "%{$search}%");
                });
            }

            if ($filters['process'] !== '' && array_key_exists($filters['process'], OutboundEmailLog::processLabels())) {
                $query->where('process', $filters['process']);
            }

            if (in_array($filters['status'], [OutboundEmailLog::STATUS_SENT, OutboundEmailLog::STATUS_FAILED], true)) {
                $query->where('status', $filters['status']);
            }

            if ($filters['sender'] !== '') {
                $query->where('sent_by_name', $filters['sender']);
            }

            if ($filters['date_from'] !== '') {
                $query->whereDate('sent_at', '>=', $filters['date_from']);
            }

            if ($filters['date_to'] !== '') {
                $query->whereDate('sent_at', '<=', $filters['date_to']);
            }

            $logs = $query->paginate(25)->withQueryString();
            $senders = OutboundEmailLog::query()
                ->whereNotNull('sent_by_name')
                ->where('sent_by_name', '!=', '')
                ->distinct()
                ->orderBy('sent_by_name')
                ->pluck('sent_by_name');

            $stats = [
                'total' => OutboundEmailLog::query()->count(),
                'sent' => OutboundEmailLog::query()->where('status', OutboundEmailLog::STATUS_SENT)->count(),
                'failed' => OutboundEmailLog::query()->where('status', OutboundEmailLog::STATUS_FAILED)->count(),
            ];
        }

        return view('sop.email-dashboard.index', [
            'logs' => $logs,
            'filters' => $filters,
            'senders' => $senders,
            'processLabels' => OutboundEmailLog::processLabels(),
            'stats' => $stats,
        ]);
    }

    public function show(OutboundEmailLog $log): View
    {
        return view('sop.email-dashboard.show', compact('log'));
    }
}
