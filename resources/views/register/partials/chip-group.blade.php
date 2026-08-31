@php
    $inputType = $type ?? 'checkbox';
    $fieldName = $name . ($inputType === 'checkbox' ? '[]' : '');
    $formKey = $oldPrefix ?? null;
    $useOld = $formKey === null || old('form_type') === $formKey;
    $oldValues = $useOld
        ? ($inputType === 'checkbox' ? old($name, []) : old($name))
        : ($inputType === 'checkbox' ? [] : null);
    $gridClass = !empty($wide) ? 'bns-admission-form__check-grid--wide' : '';
@endphp
<div class="bns-admission-form__check-grid {{ $gridClass }}">
    @foreach($options as $option)
        @php
            $checked = $inputType === 'checkbox'
                ? in_array($option, (array) $oldValues, true)
                : $oldValues === $option;
        @endphp
        <label class="bns-chip">
            <input type="{{ $inputType }}" name="{{ $fieldName }}" value="{{ $option }}" {{ $checked ? 'checked' : '' }} @if(!empty($required)) required @endif>
            <span class="bns-chip__text">{{ $option }}</span>
        </label>
    @endforeach
</div>
