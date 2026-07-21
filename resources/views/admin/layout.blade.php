<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>@yield('title', 'Admin Dashboard') | aiZAP Admin</title>
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/admin/index.css', 'resources/js/app.js'])
        @else
            <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
            <script src="{{ asset('js/admin/fallback.js') }}"></script>
        @endif
        @stack('styles')
    </head>
    <body class="@yield('body-class', 'home-page-page antialiased')">
        <div class="background-glow">
            <div class="container admin-shell">
                <div class="admin-layout">
                    @include('admin.partials.sidebar')
                    <div class="admin-main">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>