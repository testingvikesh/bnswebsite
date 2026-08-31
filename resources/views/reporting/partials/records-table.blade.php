@php
    $sourceBadgeClass = $sourceBadgeClass ?? function ($item) {
        return match ($item->resolvedFormSource()) {
            'intro-session-modal' => 'bns-reporting-badge--intro',
            'inquiry-modal' => 'bns-reporting-badge--inquiry',
            'register-quick-modal' => 'bns-reporting-badge--quick',
            'contact-page' => 'bns-reporting-badge--contact',
            default => 'bns-reporting-badge--unknown',
        };
    };
    $rows = $rows ?? collect();
@endphp

<div class="bns-reporting-scroll-hint d-none d-lg-block">
    <i class="bi bi-arrows-expand me-1"></i> Scroll horizontally to see all columns.
</div>

<div class="bns-reporting-table-wrap">
    <table class="table bns-reporting-table mb-0 align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Name</th>
                <th>Contact</th>
                <th class="d-none d-lg-table-cell">Submissions</th>
                <th class="d-none d-xl-table-cell">Reg. No.</th>
                <th class="d-none d-lg-table-cell">Form Source</th>
                <th class="d-none d-xl-table-cell">Program</th>
                <th class="d-none d-xl-table-cell">Location</th>
                <th class="d-none d-xl-table-cell">Profession Category</th>
                <th class="d-none d-xl-table-cell">Business / Company</th>
                <th class="d-none d-xl-table-cell">Business Category</th>
                <th class="d-none d-xl-table-cell">Product / Service</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $item)
                <tr>
                    <td class="text-muted small text-nowrap">{{ $item->created_at?->format('d M Y') }}<br><span class="opacity-75">{{ $item->created_at?->format('h:i A') }} IST</span></td>
                    <td class="fw-semibold text-nowrap">{{ $item->full_name }}</td>
                    <td class="small" style="min-width: 180px;">
                        <div>{{ $item->mobile }}</div>
                        <div class="text-muted">{{ Str::limit($item->email, 28) }}</div>
                    </td>
                    <td class="d-none d-lg-table-cell">
                        <span class="badge rounded-pill text-bg-secondary">{{ $item->submission_count ?? 1 }}</span>
                    </td>
                    <td class="d-none d-xl-table-cell">
                        @if($item->registration_number)
                            <span class="bns-reporting-reg">{{ $item->registration_number }}</span>
                        @else
                            —
                        @endif
                    </td>
                    <td class="d-none d-lg-table-cell"><span class="bns-reporting-badge {{ $sourceBadgeClass($item) }}">{{ $item->formSourceLabel() }}</span></td>
                    <td class="d-none d-xl-table-cell small">{{ Str::limit($item->interested_program ?? $item->subject, 24) }}</td>
                    <td class="d-none d-xl-table-cell small">{{ $item->city }}, {{ $item->state }}</td>
                    <td class="d-none d-xl-table-cell small bns-reporting-col-text" title="{{ $item->business_profession_category }}">{{ $item->business_profession_category ?: '—' }}</td>
                    <td class="d-none d-xl-table-cell small bns-reporting-col-text" title="{{ $item->organization_name }}">{{ $item->organization_name ? Str::limit($item->organization_name, 28) : '—' }}</td>
                    <td class="d-none d-xl-table-cell small bns-reporting-col-text" title="{{ $item->business_category }}">{{ $item->business_category ?: '—' }}</td>
                    <td class="d-none d-xl-table-cell small bns-reporting-col-text bns-reporting-col-text--wide" title="{{ $item->products_services }}">{{ $item->products_services ? Str::limit($item->products_services, 48) : '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="bns-reporting-empty">
                        <i class="bi bi-inbox"></i>
                        No submissions found for this section.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="bns-reporting-mobile-cards">
    @forelse($rows as $item)
        <div class="bns-reporting-mobile-card">
            <div class="bns-reporting-mobile-card__head">
                <div>
                    <div class="bns-reporting-mobile-card__name">{{ $item->full_name }}</div>
                    <div class="bns-reporting-mobile-card__meta">{{ $item->created_at?->format('d M Y, h:i A') }} IST</div>
                </div>
            </div>
            <div class="bns-reporting-mobile-card__meta">
                <div><strong>Mobile:</strong> {{ $item->mobile }}</div>
                <div><strong>Submissions:</strong> {{ $item->submission_count ?? 1 }}</div>
                <div><strong>Email:</strong> {{ $item->email }}</div>
                <div><strong>Source:</strong> {{ $item->formSourceLabel() }}</div>
                <div><strong>Program:</strong> {{ $item->interested_program ?? '—' }}</div>
                <div><strong>Location:</strong> {{ $item->city }}, {{ $item->state }}</div>
                <div><strong>Profession Category:</strong> {{ $item->business_profession_category ?: '—' }}</div>
                <div><strong>Business / Company:</strong> {{ $item->organization_name ?: '—' }}</div>
                <div><strong>Business Category:</strong> {{ $item->business_category ?: '—' }}</div>
                <div><strong>Product / Service:</strong> {{ $item->products_services ?: '—' }}</div>
            </div>
        </div>
    @empty
        <div class="bns-reporting-empty p-4">
            <i class="bi bi-inbox"></i>
            No submissions found for this section.
        </div>
    @endforelse
</div>
