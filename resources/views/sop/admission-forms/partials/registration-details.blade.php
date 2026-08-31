<h6 class="text-uppercase text-muted small fw-bold">Contact Information</h6>
<dl class="row mb-0">
    <dt class="col-sm-4">Mobile</dt><dd class="col-sm-8">{{ $record->mobile }}</dd>
    <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><a href="mailto:{{ $record->email }}">{{ $record->email }}</a></dd>
    <dt class="col-sm-4">Category</dt><dd class="col-sm-8">{{ $record->category ?? '—' }}</dd>
</dl>

@if($formFields->isNotEmpty())
<h6 class="text-uppercase text-muted small fw-bold mt-4">Form Details</h6>
<dl class="row mb-0">
    @foreach($formFields as $field)
        <dt class="col-sm-4">{{ $field['label'] }}</dt>
        <dd class="col-sm-8">{{ $field['value'] }}</dd>
    @endforeach
</dl>
@endif
