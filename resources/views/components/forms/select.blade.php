@props([
    'label',
    'type' => 'checkbox',
    'name',
    'isDisabled' => false,
    'options' => [],
    'initialSelect' => null,
    'ratioBreak' => null,
])

@php
    if (empty($id)) {
        $id = str_contains($name, '_') ? str_replace('_', '-', $name) : $name;
    }
    // unset($attributes['initialSelect']);
@endphp

@foreach ($options as $key => $value)
    <option
        {{ $attributes->merge([
            'type' => $type,
            'id' => $id . '-' . $key,
            'data-label' => $label,
            'class' => 'form-check-input i-select',
            'value' => $key,
            'disabled' => $isDisabled,
            'selected' =>
                ($key == $initialSelect)? 'selected' : null,
            'initialSelect' => null,
        ]) }}>{{ $value }}</option>
    <span class="form-check-label font-weight-normal">{{ $value }}</span>
    @if (!empty($ratioBreak) && ($key + 1) % $ratioBreak == 0)
        <br />
    @endif
@endforeach
