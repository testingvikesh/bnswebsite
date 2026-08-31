<h6 class="text-uppercase text-muted small fw-bold">Program Selection</h6>
<dl class="row mb-0">
    <dt class="col-sm-4">Category</dt><dd class="col-sm-8">{{ $record->category ?? '—' }}</dd>
    <dt class="col-sm-4">Program</dt><dd class="col-sm-8">{{ $record->program ?? '—' }}</dd>
    <dt class="col-sm-4">Year / Level</dt><dd class="col-sm-8">{{ $record->year_level ?? '—' }}</dd>
    <dt class="col-sm-4">Batch</dt><dd class="col-sm-8">{{ $record->batch ?? '—' }}</dd>
    <dt class="col-sm-4">City</dt><dd class="col-sm-8">{{ $record->city ?? '—' }}</dd>
    <dt class="col-sm-4">Centre</dt><dd class="col-sm-8">{{ $record->centre ?? '—' }}</dd>
</dl>

<h6 class="text-uppercase text-muted small fw-bold mt-4">Personal Information</h6>
<dl class="row mb-0">
    <dt class="col-sm-4">Mobile</dt><dd class="col-sm-8">{{ $record->mobile }}</dd>
    <dt class="col-sm-4">WhatsApp</dt><dd class="col-sm-8">{{ $record->whatsapp ?: '—' }}</dd>
    <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><a href="mailto:{{ $record->email }}">{{ $record->email }}</a></dd>
    <dt class="col-sm-4">Date of Birth</dt><dd class="col-sm-8">{{ $record->date_of_birth?->format('d M Y') ?? '—' }}</dd>
    <dt class="col-sm-4">Gender</dt><dd class="col-sm-8">{{ $record->gender ?? '—' }}</dd>
    <dt class="col-sm-4">Address</dt><dd class="col-sm-8">{{ $record->address ?: '—' }}</dd>
    <dt class="col-sm-4">State / PIN</dt><dd class="col-sm-8">{{ $record->state ?? '—' }} {{ $record->pin_code ? '· '.$record->pin_code : '' }}</dd>
</dl>

@if(!empty($record->parent_details))
<h6 class="text-uppercase text-muted small fw-bold mt-4">Parent / Guardian</h6>
<dl class="row mb-0">
    <dt class="col-sm-4">Name</dt><dd class="col-sm-8">{{ $record->parent_details['name'] ?? '—' }}</dd>
    <dt class="col-sm-4">Mobile</dt><dd class="col-sm-8">{{ $record->parent_details['mobile'] ?? '—' }}</dd>
</dl>
@endif

<h6 class="text-uppercase text-muted small fw-bold mt-4">Education &amp; Professional</h6>
<dl class="row mb-0">
    <dt class="col-sm-4">Qualification</dt><dd class="col-sm-8">{{ $record->education_qualification ?? '—' }}</dd>
    <dt class="col-sm-4">Institution</dt><dd class="col-sm-8">{{ $record->institution_name ?? '—' }}</dd>
    <dt class="col-sm-4">Occupation</dt><dd class="col-sm-8">{{ $record->occupation ?? '—' }}</dd>
    <dt class="col-sm-4">Experience</dt><dd class="col-sm-8">{{ $record->experience ?? '—' }}</dd>
    <dt class="col-sm-4">LinkedIn</dt><dd class="col-sm-8">@if($record->linkedin)<a href="{{ $record->linkedin }}" target="_blank">{{ $record->linkedin }}</a>@else — @endif</dd>
</dl>

@if(!empty($record->fee_breakdown))
<h6 class="text-uppercase text-muted small fw-bold mt-4">Fee Breakdown</h6>
<dl class="row mb-0">
    @foreach($record->fee_breakdown as $key => $amount)
        <dt class="col-sm-4">{{ ucwords(str_replace('_', ' ', $key)) }}</dt>
        <dd class="col-sm-8">{{ is_numeric($amount) ? '₹'.number_format((float) $amount, 2) : $amount }}</dd>
    @endforeach
</dl>
@endif
