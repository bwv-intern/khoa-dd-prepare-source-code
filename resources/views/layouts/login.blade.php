<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} | Intern PHP Source</title>

    <!-- Fonts -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- CSS Files -->
    <link href="{{ asset('css/lib/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/adminlte.min.css') }}" rel="stylesheet" />
    @vite(['resources/css/common.css'], 'build')
    @stack('styles')
</head>

<body class="hold-transition login-page">
    {{ $slot }}
    <x-loading />
    <!-- JS Files -->
    <script src="{{ asset('js/lib/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('js/lib/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('js/lib/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('js/adminlte.min.js') }}"></script>
    @vite([
        'resources/js/common.js',
        'resources/js/lib/jquery-validation/additional-setting.js',
    ], 'build')
    <script defer src="{{ asset('js/lib/custom-rules.js')}}"></script>
    <script defer src="{{ asset('js/lib/custom-messages.js')}}"></script>
    @stack('scripts')
</body>

</html>
