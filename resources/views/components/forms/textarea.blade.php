@php
    $componentName = 'forms.textarea';
    $props = [
        'label',
        'name',
        'value' => '',
        'id' => '',
        'placeholder' => null,
        'isDisabled' => false,
        'isReadonly' => false,
        'isRequired' => false,
        'rows' => 4,
    ];
    $allProps = array_merge($props, [
        'isHidden' => false,
        'valueHidden' => '',
    ]);
@endphp
@props($allProps)
@php
    if (empty($id)) {
        $id = str_contains($name, '_') ? str_replace('_', '-', $name) : $name;
    }
@endphp

<div class="input-group" style="{{ $attributes['style'] }}">
    @if (isset($label))
        <x-forms.label :label="$label" :isRequired="$isRequired"
            class="{{ 'col-2' . $attributes['classLabel'] }}" />
    @endif
    <div class="col-10">
        <span class="groupIcon">
            <textarea
                {{$attributes->merge([
                    'class' => 'form-control no-resize'. $attributes["textareaClass"],
                    'data-label' => $label,
                    'name' => $name,
                    'id' => $id,
                    'placeholder' => $placeholder,
                    'disabled' => $isDisabled,
                    'rows' => $rows,
                ])}}
            >{{$value}}</textarea>
            @if ($isHidden)
                <input type="hidden" name="{{ $name }}"
                    value={{ !empty($valueHidden) ? $valueHidden : $value }} />
            @endif
        </span>
    </div>

</div>
