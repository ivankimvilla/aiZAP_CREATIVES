<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>About Us | Aizap Creatives Studios</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/css/about-us.css', 'resources/js/pages/about-us.js'])
            @else
            <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('css/about-us.css') }}" />
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
                    <section class="page-hero">
                        <div class="hero-copy">
                            <p class="eyebrow">About Us</p>
                            <h1 class="hero-title">Learn More About <span class="brand-mark"><span class="brand-ai">ai</span><span class="brand-zap">ZAP</span></span></h1>
                            <p class="hero-sub">We are a creative studio specializing in AI-powered content, brand storytelling, and digital campaigns that help businesses connect with modern audiences.</p>
                        </div>

                        <div class="hero-plate">
                            <p class="plate-mark"><span class="brand-ai">ai</span><span class="brand-zap">ZAP</span> Creatives</p>
                            <div class="plate-line"></div>
                            <div class="plate-stat">
                                <span class="plate-num">120+</span>
                                <span class="plate-label">Campaigns Shipped</span>
                            </div>
                            <div class="plate-stat">
                                <span class="plate-num">4.9/5</span>
                                <span class="plate-label">Avg. Client Rating</span>
                            </div>
                            <div class="plate-stat">
                                <span class="plate-num">24hr</span>
                                <span class="plate-label">Avg. First Draft</span>
                            </div>
                        </div>
                    </section>

                    {{-- ============ OUR MISSION ============ --}}
                    <section class="services-strip">
                        <div class="section-header-row">
                            <div>
                                <p class="section-eyebrow">Our Mission</p>
                                <h2 class="section-title">We Turn AI Ideas Into Unforgettable Stories</h2>
                            </div>
                            <p class="section-copy">AI Creatives blends strategic insight, cinematic visuals, and rapid execution to help brands launch campaigns that feel premium and perform fast.</p>
                        </div>

                        <div class="value-grid">
                            @foreach ([
                                ['title' => 'Strategy & Concept', 'text' => 'Build campaign concepts grounded in business goals, audience insights, and brand voice.', 'key' => 'strategy-concept'],
                                ['title' => 'AI Video Production', 'text' => 'Create next-gen commercial ads, trailers, and social films using AI-powered workflows.'],
                                ['title' => 'Brand Storytelling', 'text' => 'Craft memorable narratives that connect emotionally across every channel.'],
                                ['title' => 'Digital Growth', 'text' => 'Launch performance-driven content that delivers reach, engagement, and conversions.'],
                            ] as $index => $value)
                                @php
                                    $sectionVideo = isset($value['key']) ? ($sectionVideos[$value['key']] ?? null) : null;
                                @endphp
                                <div class="value-card{{ $sectionVideo ? ' value-card--feature' : '' }}">
                                        @if ($sectionVideo && $sectionVideo->video_url)
                                        <video
                                            class="value-video"
                                            src="{{ $sectionVideo->video_url }}"
                                            poster="{{ $sectionVideo->poster_url }}"
                                            autoplay
                                            muted
                                            loop
                                            playsinline
                                            preload="metadata"
                                        ></video>
                                        <div class="value-video-overlay"></div>
                                        <button type="button" class="expand-btn" aria-label="Expand video">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M3 9V3h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M21 15v6h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M21 3l-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M3 21l7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    @endif
                                    <span class="value-index">{{ sprintf('%02d', $index + 1) }}</span>
                                    <p class="value-title">{{ $value['title'] }}</p>
                                    <p class="value-text">{{ $value['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- ============ WHY CLIENTS CHOOSE US ============ --}}
                    <section class="reasons-section">
                        <div class="reasons-layout">
                            <div class="reasons-list">
                                @foreach ([
                                    ['title' => 'Fast Turnarounds', 'subtitle' => 'High-quality content ready in days, not weeks.'],
                                    ['title' => 'Creative Control', 'subtitle' => 'Flexible iterations until the story feels right.'],
                                    ['title' => 'Data-Led Decisions', 'subtitle' => 'Content optimized for both brand and performance.'],
                                    ['title' => 'Growth-Focused', 'subtitle' => 'Every asset is designed to move audiences and sales.'],
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

                            <div class="reasons-intro">
                                <span class="reasons-tag">The aiZAP Difference</span>
                                <h2>Why Clients Choose Us</h2>
                                <p class="reasons-lead">Four things that hold true on every project we take on, from the first call to final delivery.</p>

                                <div class="reasons-video">
                                    @php $reasonsVideo = $sectionVideos['why-clients-choose-us'] ?? null; @endphp
                                    @if ($reasonsVideo && $reasonsVideo->video_url)
                                        <video
                                            src="{{ $reasonsVideo->video_url }}"
                                            poster="{{ $reasonsVideo->poster_url }}"
                                            autoplay
                                            muted
                                            loop
                                            playsinline
                                            preload="metadata"
                                        ></video>
                                    @else
                                        <video
                                            src="{{ asset('videos/why-clients-choose-us.mp4') }}"
                                            poster="{{ asset('images/why-clients-choose-us-poster.jpg') }}"
                                            autoplay
                                            muted
                                            loop
                                            playsinline
                                            preload="metadata"
                                        ></video>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>
                </main>
                @include('partials.footer')

            </div>
        </div>
    </body>
</html>