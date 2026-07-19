<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Process | aiZAP CREATIVES</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/css/process.css', 'resources/js/pages/process.js'])
            @else
            <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <script src="{{ asset('js/app.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('css/process.css') }}" />
             <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/button.css') }}" />
        @endif
    </head>
    <body class="home-page-page process-page antialiased">
        <div class="background-glow">
            <div class="container">
                @include('partials.header')

                <main class="page-main">
                    <section class="page-hero">
                        <div class="hero-copy">
                            <p class="eyebrow">Process</p>
                            <h1 class="hero-title">Our <span class="gold">Workflow</span></h1>
                            <p class="hero-sub">Discover how we move from concept to execution with AI-driven creative strategy, production, refinement, and delivery.</p>
                        </div>

                        <div class="hero-media">
                            <div class="process-media-label">
                                <span>Behind The Work</span>
                            </div>

                            @php
                                $processGallery = [
                                    ['image' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600&q=80', 'number' => '01', 'caption' => 'Discovery', 'text' => 'We audit your brand, market, and audience.'],
                                    ['image' => 'https://images.unsplash.com/photo-1522199755839-a2bacb67c546?auto=format&fit=crop&w=600&q=80', 'number' => '02', 'caption' => 'Concept', 'text' => 'We create bold story ideas that move attention.'],
                                    ['image' => 'https://images.unsplash.com/photo-1492724441997-5dc865305da7?auto=format&fit=crop&w=600&q=80', 'number' => '03', 'caption' => 'Production', 'text' => 'We build visuals and video with an AI workflow.'],
                                    ['image' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?auto=format&fit=crop&w=600&q=80', 'number' => '04', 'caption' => 'Refinement', 'text' => 'We polish and test every asset for impact.'],
                                    ['image' => 'https://images.unsplash.com/photo-1533750349088-cd871a92f312?auto=format&fit=crop&w=600&q=80', 'number' => '05', 'caption' => 'Launch', 'text' => 'We deploy the work and measure results.'],
                                ];
                            @endphp

                            <div class="process-scroll-wrap">
                                <div class="process-scroll">
                                    @foreach ($processGallery as $shot)
                                        <div class="process-scroll-item">
                                            <img src="{{ $shot['image'] }}" alt="{{ $shot['caption'] }}" loading="lazy" />
                                            <span class="process-scroll-number">{{ $shot['number'] }}</span>
                                            <div class="process-scroll-info">
                                                <span class="process-scroll-caption">{{ $shot['caption'] }}</span>
                                                <span class="process-scroll-text">{{ $shot['text'] }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="process-scroll-arrow" aria-label="Scroll gallery">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M7 8l-4 4 4 4" />
                                        <path d="M17 8l4 4-4 4" />
                                        <path d="M3 12h18" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </section>

                    <section class="process-section">
                        <p class="section-eyebrow">How We Deliver</p>
                        <h2 class="section-title">A five-step process for creative clarity</h2>

                        <div class="process-steps">
                            @foreach ([
                                ['number' => '1', 'title' => 'Discover', 'text' => 'We audit your brand, market, and audience for every campaign.'],
                                ['number' => '2', 'title' => 'Concept', 'text' => 'We create bold story ideas that move attention and action.'],
                                ['number' => '3', 'title' => 'Produce', 'text' => 'We develop visuals, video, and assets with AI performance workflow.'],
                                ['number' => '4', 'title' => 'Refine', 'text' => 'We polish and test each asset to maximize impact.'],
                                ['number' => '5', 'title' => 'Launch', 'text' => 'We deploy work across the right channels and measure results.'],
                            ] as $step)
                                <div class="process-step">
                                    <div class="step-icon-wrap">
                                        <div class="step-icon-box">
                                            <svg viewBox="0 0 24 24"><path d="M12 2.5c2.5 2 4 5 4 9 0 2-.6 3.6-1.2 4.8L12 19l-2.8-2.7C8.6 15.1 8 13.5 8 11.5c0-4 1.5-7 4-9z"/></svg>
                                        </div>
                                        <span class="step-number">{{ $step['number'] }}</span>
                                    </div>
                                    <div class="step-content">
                                        <h4>{{ $step['title'] }}</h4>
                                        <p>{{ $step['text'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    <section class="stats-bar">
                        <div class="stats-bar-inner">
                            <div class="stats-grid">
                                @foreach ([
                                    ['value' => '5', 'label' => 'Steps to launch'],
                                    ['value' => '48h', 'label' => 'First draft cycle'],
                                    ['value' => '100%', 'label' => 'Aim for impact'],
                                ] as $metric)
                                    <div class="stat-item">
                                        <p class="stat-value">{{ $metric['value'] }}</p>
                                        <p class="stat-label">{{ $metric['label'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="stats-cta">
                                <div>
                                    <h3>Want a smoother creative process?</h3>
                                    <p>We keep entire campaigns on track with clear milestones and fast review loops.</p>
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