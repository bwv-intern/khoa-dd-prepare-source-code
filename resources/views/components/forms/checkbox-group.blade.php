@props([
    'label',
    'type' => 'checkbox',
    'name',
    'isRequired' => false,
    'isDisabled' => false,
    'options' => [],
    'valueChecked' => [],
    'ratioBreak' => null,
])

<div class="input-group">
    @if (isset($label))
        <x-forms.label :label="$label" :isRequired="$isRequired" class="{{ 'col-2' . $attributes['classLabel'] }}" />
    @endif
    <x-forms.checkbox
        :label="$label"
        :type="$type"
        :name="$name"
        :isDisabled="$isDisabled"
        :options="$options"
        :valueChecked="$valueChecked"
        :ratioBreak="$ratioBreak"
        data-default="{{ $attributes['data-default'] }}"
        class="{{ $attributes['classCheckbox'] }}"
    />
</div>
