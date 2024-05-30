@php
    $componentName = 'forms.text';
    $props = [
        'label',
        'type' => 'text',
        'name',
        'value' => '',
        'idSelector' => '',
        'placeholder' => '',
        'isDisabled' => false,
        'isReadonly' => false,
        'isRequired' => false,
        'icon' => '',
    ];
    $allProps = array_merge($props, [
        'isHidden' => false,
        'valueHidden' => '',
    ]);
@endphp
@props($allProps)

<div class="input-group" style="{{ $attributes['style'] }}">
    @if (isset($label))
        <x-forms.label :label="$label" :isRequired="$isRequired" class="{{ 'col-2'. $attributes['classLabel'] }}" />
    @endif
    <div class="col-10">
        <span class="groupIcon">
            <x-forms.text :type="$type"
                :label="$label"
                :name="$name"
                :value="$value"
                :idSelector="$idSelector" :placeholder="$placeholder"
                :isDisabled="$isDisabled"
                :isHidden="$isHidden"
                class="{{ $attributes['classInput'] }}" />
            @if (!empty($icon))
                @php
                    $iconIdSelector = (!empty($idSelector))? $idSelector : (
                        (str_contains($name, '_')
                            ? str_replace('_', '-', $name)
                            : $name)) . '-icon';
                @endphp
                <i class="{{ $icon }}" id="{{ $iconIdSelector }}"></i>
            @endif
            @if ($isHidden)
                <input
                    type="hidden"
                    name="{{ $name }}"
                    value={{ !empty($valueHidden) ? $valueHidden : $value }}
                />
            @endif
        </span>
    </div>

</div>
