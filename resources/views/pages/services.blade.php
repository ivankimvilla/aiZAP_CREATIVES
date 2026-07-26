<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Services | Aizap Creatives Studios</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/css/services.css', 'resources/js/pages/services.js'])
            @else
            <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('css/services.css') }}" />
             <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/button.css') }}" />
        @endif
    </head>
    <body class="home-page-page antialiased">
        <div class="background-glow">
            <div class="container">
                @include('partials.header')

                <main class="page-main">

                    {{-- ============ PAGE HERO ============ --}}
                    {{-- The video that used to live in a repeated
                         "Services / What We Do" block below now lives
                         here, next to the one and only intro copy. --}}
                    <section class="page-hero">
                        <div class="hero-copy">
                            <p class="eyebrow">Services</p>
                            <h1 class="hero-title">What <span class="gold">We Do</span></h1>
                            <p class="hero-sub">AI Creatives delivers premium ads, short films, brand campaigns, and social-first content powered by machine learning and visual storytelling.</p>
                        </div>

                        <div class="services-feature-video">
                            @php $servicesVideo = $sectionVideos['services-what-we-do'] ?? null; @endphp
                            <video
                                id="services-what-we-do-video"
                                class="services-feature-video__media"
                                src="{{ $servicesVideo->video_url ?? asset('videos/services-what-we-do.mp4') }}"
                                poster="{{ $servicesVideo->poster_url ?? asset('images/services-what-we-do-poster.jpg') }}"
                                autoplay
                                muted
                                loop
                                playsinline
                                preload="metadata"
                            ></video>
                        </div>
                    </section>

                    {{-- ============ SERVICE OFFERINGS ============ --}}
                    <section class="services-strip">
                        <div class="section-header-row">
                            <div>
                                <p class="section-eyebrow">Capabilities</p>
                                <h2 class="section-title">Five Ways We Bring Ideas To Life</h2>
                            </div>
                            <p class="section-copy">From concept to distribution, each service is built to move fast without losing craft.</p>
                        </div>

                        <div class="value-grid">
                            @foreach ([
                                ['title' => 'AI Commercial Ads', 'text' => 'High-impact video campaigns for social and streaming platforms.'],
                                ['title' => 'AI Product Ads', 'text' => 'Product launches that blend storytelling with conversion-first visuals.'],
                                ['title' => 'Storytelling & Drama', 'text' => 'Narrative-driven films and series for emotional brand connection.'],
                                ['title' => 'Short Films', 'text' => 'Cinematic branded shorts designed for digital audiences.'],
                                ['title' => 'Movie Trailers', 'text' => 'Epic trailers, teasers, and launch films with a cinematic edge.'],
                            ] as $index => $service)
                                <div class="value-card">
                                    <span class="value-index">{{ sprintf('%02d', $index + 1) }}</span>
                                    <p class="value-title">{{ $service['title'] }}</p>
                                    <p class="value-text">{{ $service['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- ============ FROM IDEA TO LAUNCH ============ --}}
                    <section class="reasons-section">
                        <div class="reasons-layout">
                            <div class="reasons-intro">
                                <span class="reasons-tag">The Process</span>
                                <h2>From Idea To Launch</h2>
                                <p class="reasons-lead">Three stages, one team, no handoffs lost in translation.</p>

                                <div class="reasons-video">
                                    @php $processVideo = $sectionVideos['services-process'] ?? null; @endphp
                                    <video
                                        id="services-process-video"
                                        src="{{ $processVideo->video_url ?? asset('videos/idea-to-launch.mp4') }}"
                                        poster="{{ $processVideo->poster_url ?? asset('images/idea-to-launch-poster.jpg') }}"
                                        autoplay
                                        muted
                                        loop
                                        playsinline
                                        preload="metadata"
                                    ></video>
                                    <button type="button" class="expand-btn" aria-label="Expand video">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                            <path d="M3 9V3h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M21 15v6h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M21 3l-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M3 21l7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="reasons-list">
                                @foreach ([
                                    ['title' => 'Creative Strategy', 'subtitle' => 'We build creative plans around your goals.'],
                                    ['title' => 'Production', 'subtitle' => 'AI-enhanced production with fast iteration.'],
                                    ['title' => 'Distribution', 'subtitle' => 'Assets optimized for socials and channels.'],
                                ] as $index => $item)
                                    <div class="reason-row">
                                        <span class="reason-num">{{ sprintf('%02d', $index + 1) }}</span>
                                        <div class="reason-copy">
                                            <h3>{{ $item['title'] }}</h3>
                                            <p>{{ $item['subtitle'] }}</p>
                                        </div>
                                        <span class="reason-check">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M5 12.5l4.5 4.5L19 7"/>
                                            </svg>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>
                </main>
                @include('partials.footer')

            </div>
        </div>
    </body>
</html>