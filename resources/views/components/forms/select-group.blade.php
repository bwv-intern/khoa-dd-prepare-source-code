@props([
    'label',
    'type' => 'checkbox',
    'name',
    'isRequired' => false,
    'isDisabled' => false,
    'options' => [],
    'initialSelect' => null,
    'ratioBreak' => null,
])

<div class="input-group">
    <select class="{{'form-select ' . $attributes['class']}}" name="{{ $name }}">
        <x-forms.select
        :label="$label"
        :type="$type"
        :name="$name"
        :isDisabled="$isDisabled"
        :options="$options"
        :initialSelect="$initialSelect"
        :ratioBreak="$ratioBreak"
        class="{{ $attributes['classCheckbox'] }}"
        />
    </select>
</div>
