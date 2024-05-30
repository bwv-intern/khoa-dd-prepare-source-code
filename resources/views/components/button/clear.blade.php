@props([
    'label' => 'クリア',
    'screen' => '',
    'id' =>'btn-clear'
])

<button
	type="button" class="btn btn-round btn-danger btn-clear-search"
	data-url="{{ route('ADMIN_COMMON_RESET_SEARCH') }}"
    data-screen="{{ $screen }}"
    id="{{ $id }}"
>
    {{ $label }}
</button>
