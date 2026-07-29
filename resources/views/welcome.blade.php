<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Welcome</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if ((file_exists(public_path('build/manifest.json')) && filesize(public_path('build/manifest.json')) > 0) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script type="module" src="{{ asset('js/app.js') }}"></script>
        @endif
    </head>
    <body class="min-h-screen bg-white text-slate-900 antialiased">
        <div class="flex min-h-screen items-center justify-center px-6 py-12">
            <div class="max-w-xl text-center">
                <h1 class="text-4xl font-semibold tracking-tight text-slate-900 sm:text-5xl">Welcome to AI Creatives</h1>
                <p class="mt-6 text-base leading-8 text-slate-600">This is the default welcome page. The custom homepage is available at the site root and is served by <code>resources/views/home-page.blade.php</code>.</p>
            </div>
        </div>
    </body>
</html>
