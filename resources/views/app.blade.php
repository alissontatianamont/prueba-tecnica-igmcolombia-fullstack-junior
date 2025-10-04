<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Sistema de Facturación') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Scripts -->
    @if(app()->environment('production'))
        @php
            $manifestPath = public_path('build/manifest.json');
            if (file_exists($manifestPath)) {
                $manifest = json_decode(file_get_contents($manifestPath), true);
                $cssFile = $manifest['resources/css/app.css']['file'] ?? null;
                $jsFile = $manifest['resources/js/app.js']['file'] ?? null;
                // Forzar HTTPS en las URLs
                $baseUrl = str_replace('http://', 'https://', config('app.url'));
            }
        @endphp
        @if(isset($cssFile))
            <link rel="stylesheet" href="{{ $baseUrl }}/build/{{ $cssFile }}">
        @endif
        @if(isset($jsFile))
            <script type="module" src="{{ $baseUrl }}/build/{{ $jsFile }}"></script>
        @endif
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans antialiased">
    <div id="app"></div>
</body>
</html>