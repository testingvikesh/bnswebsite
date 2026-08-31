@php
    $cols = $cols ?? 4;
    $fieldId = $id ?? $name;
@endphp
<div class="col-md-{{ $cols }}">
    <span class="bns-admission-form__label">{{ $label }}</span>
    <div class="bns-admission-form__inline-options">
        @foreach(['yes' => 'Yes', 'no' => 'No'] as $value => $labelText)
            <label class="bns-chip">
                <input type="radio" name="{{ $name }}" value="{{ $value }}" id="{{ $fieldId }}_{{ $value }}" {{ old($name) === $value ? 'checked' : '' }}>
                <span class="bns-chip__text">{{ $labelText }}</span>
            </label>
        @endforeach
    </div>
    @error($name)<div class="text-danger small mt-1">{{ $message }}</div>@enderror
</div>
