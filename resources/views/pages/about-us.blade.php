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
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('css/about-us.css') }}" />
        @endif
    </head>
    <body class="home-page-page antialiased">
        <div class="background-glow">
            <div class="container">
                @include('partials.header')

                <main class="page-main">

                    {{-- ============ PAGE HERO ============ --}}
                    <section class="hero-section about-hero-section">
                        <div class="hero-left">
                            <p class="eyebrow">About Us</p>
                            <h1 class="hero-title">
                                We're More Than<br>
                                Creators.<br>
                                <span class="gold">We're Your Creative<br>Partners.</span>
                            </h1>
                            <p class="hero-sub">At AI Creatives, we combine the power of artificial intelligence with human imagination to create content that connects, inspires, and delivers real results.</p>
                            <p class="hero-sub">From high-converting ads to cinematic storytelling, we help brands communicate with impact in the digital world.</p>
                        </div>

                        <div class="hero-panels">
                            <div class="hero-panel-media" style="background-image:url('{{ asset('about-us.png') }}')"></div>
                        </div>
                    </section>

                    {{-- ============ WHY WE'RE DIFFERENT ============ --}}
                    <section class="features-section">
                        <div class="value-grid">
                            @foreach ([
                                [
                                    'icon' => '<path d="M9 4a3 3 0 00-3 3v1a3 3 0 00-2 2.8V13a3 3 0 002 2.8v1A3 3 0 009 20h1V4H9z"/><path d="M15 4a3 3 0 013 3v1a3 3 0 012 2.8V13a3 3 0 01-2 2.8v1a3 3 0 01-3 3.2h-1V4h1z"/><path d="M9 8h2M9 12h2M9 16h2M13 8h2M13 12h2M13 16h2"/>',
                                    'title' => 'AI-Powered Creativity',
                                    'text' => 'We leverage cutting-edge AI tools to produce high-quality content faster, smarter, and better.',
                                ],
                                [
                                    'icon' => '<circle cx="8.5" cy="8" r="3"/><path d="M2.5 19c0-3 2.7-5 6-5s6 2 6 5"/><circle cx="16.5" cy="9" r="2.4"/><path d="M14.5 19c.3-2.2 2-4 4-4.3"/>',
                                    'title' => "Human Touch, Real Impact",
                                    'text' => 'Behind every AI-generated piece is a team of storytellers, editors, and designers who care.',
                                ],
                                [
                                    'icon' => '<circle cx="12" cy="12" r="8"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="1"/>',
                                    'title' => 'Focused On Results',
                                    'text' => "We create content that doesn't just look good — it works. Built for engagement, conversions, and growth.",
                                ],
                                [
                                    'icon' => '<path d="M12 2c3 2 5 6 5 10 0 2-1 4-2 5l-1 3-2-2-2 2-1-3c-1-1-2-3-2-5 0-4 2-8 5-10z"/><circle cx="12" cy="10" r="1.6"/><path d="M9 17l-2 3M15 17l2 3"/>',
                                    'title' => 'Built For The Future',
                                    'text' => 'We stay ahead of trends and technology so your brand always stays one step ahead.',
                                ],
                            ] as $feature)
                                <div class="value-card">
                                    <div class="value-icon">
                                        <svg viewBox="0 0 24 24">{!! $feature['icon'] !!}</svg>
                                    </div>
                                    <p class="value-title">{{ $feature['title'] }}</p>
                                    <p class="value-text">{{ $feature['text'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </section>

                    {{-- ============ NUMBERS THAT TELL OUR STORY ============ --}}
                    <section class="impact-section">
                        <div class="impact-panel">
                            <div class="impact-intro">
                                <p class="section-eyebrow">Our Impact</p>
                                <h2>Numbers That<br>Tell Our Story</h2>
                            </div>

                            <div class="impact-stats">
                                @foreach ([
                                    [
                                        'icon' => '<rect x="3" y="3" width="18" height="18" rx="4"/><path d="M10 8.5l6 3.5-6 3.5v-7z"/>',
                                        'num' => '500+',
                                        'label' => 'Projects Completed',
                                        'desc' => 'Across different industries and platforms',
                                    ],
                                    [
                                        'icon' => '<circle cx="8" cy="9" r="3"/><circle cx="16" cy="9" r="3"/><path d="M2.5 19c0-3 2.7-5 5.5-5s5.5 2 5.5 5M10.5 19c0-2.8 2.4-5 5.5-5s5.5 2.2 5.5 5"/>',
                                        'num' => '100+',
                                        'label' => 'Happy Clients',
                                        'desc' => 'Brands that trust us with their story',
                                    ],
                                    [
                                        'icon' => '<path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"/><circle cx="12" cy="12" r="3"/>',
                                        'num' => '50M+',
                                        'label' => 'Views Generated',
                                        'desc' => 'Content that connects and gets results',
                                    ],
                                    [
                                        'icon' => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 4 5.8 4 9s-1.5 6.5-4 9c-2.5-2.5-4-5.8-4-9s1.5-6.5 4-9z"/>',
                                        'num' => 'Global',
                                        'label' => 'Client Reach',
                                        'desc' => 'Working with brands around the world',
                                    ],
                                ] as $stat)
                                    <div class="impact-stat">
                                        <div class="impact-icon">
                                            <svg viewBox="0 0 24 24">{!! $stat['icon'] !!}</svg>
                                        </div>
                                        <span class="impact-num">{{ $stat['num'] }}</span>
                                        <span class="impact-label">{{ $stat['label'] }}</span>
                                        <p class="impact-desc">{{ $stat['desc'] }}</p>
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