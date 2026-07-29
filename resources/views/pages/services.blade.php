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
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('css/services.css') }}" />
        @endif
    </head>
    <body class="home-page-page antialiased">
        <div class="background-glow">
            <div class="container">
                @include('partials.header')

                <main class="page-main">

                    {{-- ============ PAGE HERO ============ --}}
                    <section class="page-hero">
                        <div class="hero-copy">
                            <p class="eyebrow">Our Services</p>
                            <h1 class="hero-title">Creative Solutions.<br><span class="gold">Limitless Possibilities.</span></h1>
                            <p class="hero-sub">From concept to final cut, we provide end-to-end AI-powered creative services that help brands tell stories, drive engagement, and achieve real results.</p>
                        </div>

                        <div class="hero-panels">
                            <div class="hero-panel-media" style="background-image:url('{{ asset('services.png') }}')"></div>
                        </div>
                    </section>

                    {{-- ============ SERVICE OFFERINGS ============ --}}
                    <section class="services-strip">
                        <div class="services-intro">
                            <p class="section-eyebrow">What We Do</p>
                            <h2 class="section-title">Our Services</h2>
                        </div>

                        <div class="value-grid value-grid--row">
                            @foreach ([
                                [
                                    'title' => 'AI Commercial Ads',
                                    'text' => 'High-impact video campaigns for social and streaming platforms.',
                                    'image' => 'images/services/ai-commercial-ads.jpg',
                                    'icon' => '<path d="M4 8h16v11a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8Z"/><path d="m4 8 1.5-4h3L7 8"/><path d="m10 8 1.5-4h3L13 8"/><path d="m16 8 1.5-4h3L19 8"/>',
                                ],
                                [
                                    'title' => 'AI Product Ads',
                                    'text' => 'Product launches that blend storytelling with conversion-first visuals.',
                                    'image' => 'images/services/ai-product-ads.jpg',
                                    'icon' => '<path d="M6 8V6a4 4 0 0 1 8 0v2"/><rect x="4" y="8" width="16" height="13" rx="2"/>',
                                ],
                                [
                                    'title' => 'AI Storytelling / Drama',
                                    'text' => 'Narrative-driven films and series for emotional brand connection.',
                                    'image' => 'images/services/ai-storytelling-drama.jpg',
                                    'icon' => '<circle cx="9" cy="10" r="3"/><path d="M14 8.5c1.5.5 2.5 1.9 2.5 3.5s-1 3-2.5 3.5"/><path d="M3 20c1-3 3.5-5 6-5s5 2 6 5"/>',
                                ],
                                [
                                    'title' => 'AI Movie Trailers',
                                    'text' => 'Epic trailers, teasers, and launch films with a cinematic edge.',
                                    'image' => 'images/services/ai-movie-trailers.jpg',
                                    'icon' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="m10 9 5 3-5 3V9Z"/>',
                                ],
                                [
                                    'title' => 'UGC-style AI Videos',
                                    'text' => 'Authentic, creator-style clips built to perform in social feeds.',
                                    'image' => 'images/services/ugc-style-ai-videos.jpg',
                                    'icon' => '<circle cx="12" cy="8" r="3.5"/><path d="M5 20c1.2-3.5 4-5.5 7-5.5s5.8 2 7 5.5"/>',
                                ],
                                [
                                    'title' => 'Explainer Videos',
                                    'text' => 'Clear, engaging breakdowns that turn complex ideas into simple stories.',
                                    'image' => 'images/services/explainer-videos.jpg',
                                    'icon' => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8"/><path d="M10.5 9.5l3 1.8-3 1.8v-3.6Z"/>',
                                ],
                            ] as $service)
                                <div class="value-card">
                                    <span class="value-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            {!! $service['icon'] !!}
                                        </svg>
                                    </span>
                                    <p class="value-title">{{ $service['title'] }}</p>
                                    <p class="value-text">{{ $service['text'] }}</p>
                                    <span class="value-image">
                                        <img src="{{ asset($service['image']) }}" alt="{{ $service['title'] }}" loading="lazy">
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- ============ OUR APPROACH ============ --}}
                    <section class="approach-section">
                        <div class="approach-intro">
                            <p class="section-eyebrow">Our Approach</p>
                            <h2 class="section-title">Simple Process. Powerful Results.</h2>
                        </div>

                        <div class="process-steps">
                            @foreach ([
                                [
                                    'num' => '01',
                                    'title' => 'Discovery',
                                    'text' => 'We learn about your goals, audience, and vision.',
                                    'icon' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>',
                                ],
                                [
                                    'num' => '02',
                                    'title' => 'Strategy & Concept',
                                    'text' => 'We craft the right strategy and creative concept.',
                                    'icon' => '<path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 2a7 7 0 0 0-4 12.7V17h8v-2.3A7 7 0 0 0 12 2Z"/>',
                                ],
                                [
                                    'num' => '03',
                                    'title' => 'Production',
                                    'text' => 'We produce stunning AI-powered content.',
                                    'icon' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/>',
                                ],
                                [
                                    'num' => '04',
                                    'title' => 'Edit & Refine',
                                    'text' => 'We polish every detail until it\'s perfect.',
                                    'icon' => '<circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M20 4 8.12 15.88"/><path d="M14.47 14.48 20 20"/><path d="M8.12 8.12 12 12"/>',
                                ],
                                [
                                    'num' => '05',
                                    'title' => 'Delivery',
                                    'text' => 'We deliver on time, ready to make an impact.',
                                    'icon' => '<path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/>',
                                ],
                            ] as $step)
                                <div class="process-step">
                                    <span class="process-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                            {!! $step['icon'] !!}
                                        </svg>
                                    </span>
                                    <p class="process-num">{{ $step['num'] }}</p>
                                    <p class="process-title">{{ $step['title'] }}</p>
                                    <p class="process-text">{{ $step['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- ============ FINAL CTA BANNER ============ --}}
                    <div class="final-cta-banner">
                        <span class="final-cta-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                                <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                                <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                                <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>
                            </svg>
                        </span>
                        <div class="final-cta-copy">
                            <h2>Ready to Bring Your Story to Life?</h2>
                            <p>Let's create something extraordinary together.</p>
                        </div>
                        <button type="button" class="btn btn-gold contact-toggle" aria-label="Open contact form">Let's Work Together</button>
                    </div>
                </main>
                @include('partials.footer')

            </div>
        </div>
    </body>
</html>