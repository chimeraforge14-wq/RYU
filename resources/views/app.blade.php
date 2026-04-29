<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1e293b">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <title inertia>{{ config('app.name', 'e-Rapor SD Modern') }}</title>

    <!-- Scripts & Styles -->
    @if(class_exists(\Tightenco\Ziggy\ZiggyServiceProvider::class))
        @routes
    @endif
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @inertiaHead
</head>
<body class="font-sans antialiased bg-[#0f172a] text-white">
    @inertia
</body>
</html>
