<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Explainer Videos - aizap creatives studios</title>
       <link rel="stylesheet" href="{{ asset('css/what we do/explainer-videos.css') }}" />
               <link rel="stylesheet" href="{{ asset('css/what-we-do.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
    </head>
    <body class="page-main antialiased">
        <div class="container">
            <header class="page-only-header" role="banner">
                <div class="header-inner" style="display:flex;align-items:flex-start;gap:12px;margin:6px 0;">
                    <a href="{{ url('/') }}" class="btn btn-outline btn-sm page-back" aria-label="Home">← Back</a>
                    <div style="flex:1"></div>
                </div>
            </header>
            <section class="hero-section">
            <div class="hero-left">
                <h1 class="hero-title">Explainer Videos</h1>
                <p class="hero-sub">Clear, concise explainers that communicate product value and reduce friction.</p>
            </div>
                  @include('partials.what-we-do-nav')
        </section>{{-- ============ WHAT WE DO (links) ============ --}}
              @include('partials.category-projects', ['category' => 'explainer-videos', 'label' => 'Explainer Videos'])
        
            @include('partials.booking-calendar')
            @include('partials.footer')
        </div>
    </body>
</html>


