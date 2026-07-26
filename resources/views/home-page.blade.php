<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Aizap Creatives Studios</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/js/pages/home.js'])
            @else
            <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/button.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
        @endif
    </head>
    <body class="home-page-page home-page antialiased">
        <div class="background-glow">
            <div class="container">

                @include('partials.header')

                {{-- ============ HERO ============ --}}
                <section class="hero-section">
                    <div class="hero-left">
                        <p class="eyebrow">Ideas. Stories. Impact.</p>
                        <h1 class="hero-title">
                            AI-Powered<br>
                            Creativity<br>
                            That <span class="gold">Builds</span> Brands.
                        </h1>
                        <p class="hero-sub">We create high-quality AI-generated videos, ads, and creative content that help brands stand out, connect, and grow in the digital world.</p>
                        <div class="hero-actions">
                            <a href="{{ url('/portfolio') }}" class="btn btn-primary">View Our Work</a>
                            @include('partials.book-call-button', ['class' => 'btn btn-outline'])
                        </div>
                    </div>

                    <div class="hero-panels">
                        <div class="hero-panel-img panel-1">
                            <div class="hero-panel-media" style="background-image:url('https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80')"></div>
                        </div>
                        <div class="hero-panel-img panel-2">
                            <div class="hero-panel-media" style="background-image:url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=900&q=80')"></div>
                        </div>
                        <div class="hero-panel-img panel-3">
                            <div class="hero-panel-media" style="background-image:url('https://images.unsplash.com/photo-1614728263952-84ea256f9679?auto=format&fit=crop&w=900&q=80')"></div>
                        </div>
                        <div class="hero-panel-img panel-4">
                            <div class="hero-panel-media" style="background-image:url('https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=900&q=80')"></div>
                        </div>
                    </div>
                </section>

                {{-- ============ WHAT WE DO ============ --}}
                <section class="services-strip">
                    <p class="section-eyebrow">What We Do</p>
                    <h2 class="section-title">Creative Solutions For Every Brand</h2>

                    <div class="services-grid">
                        @foreach ([
                            ['label' => 'AI Commercial Ads', 'icon' => '<path d="M4 9h16v10a1 1 0 01-1 1H5a1 1 0 01-1-1V9z"/><path d="M4 9l1.5-4h3L7 9M9.5 9L11 5h3l-1.5 4M15 9l1.5-4h3L18 9"/>', 'url' => '/what-we-do/ai-commercial-ads'],
                            ['label' => 'AI Product Ads', 'icon' => '<path d="M6 8V6a3 3 0 016 0v2M3.5 8h13l-1 12h-11l-1-12z"/><circle cx="17.5" cy="6.5" r="2.2"/><path d="M17.5 5.3v2.4M16.3 6.5h2.4"/>', 'url' => '/what-we-do/ai-product-ads'],
                            ['label' => 'AI Storytelling / Drama', 'icon' => '<circle cx="8.5" cy="12" r="5.5"/><circle cx="15.5" cy="12" r="5.5"/>', 'url' => '/what-we-do/ai-storytelling-drama'],
                            ['label' => 'AI Short Films', 'icon' => '<rect x="3" y="7" width="12" height="10" rx="1.5"/><path d="M15 10l6-3v10l-6-3z"/>', 'url' => '/what-we-do/ai-short-films'],
                            ['label' => 'AI Movie Trailers', 'icon' => '<rect x="6" y="2.5" width="12" height="19" rx="2"/><path d="M10 18.5h4" /><path d="M10 6h4v3h-4z"/>', 'url' => '/what-we-do/ai-movie-trailers'],
                            ['label' => 'AI Brand Campaigns', 'icon' => '<path d="M3 11v2a1 1 0 001 1h2l4 4V6L6 10H4a1 1 0 00-1 1z"/><path d="M15 9a3 3 0 010 6M17.5 6.5a7 7 0 010 11"/>', 'url' => '/what-we-do/ai-brand-campaigns'],
                            ['label' => 'Social Media Content', 'icon' => '<rect x="6" y="2.5" width="12" height="19" rx="2"/><path d="M12 16.5a1.6 1.6 0 100 3.2 1.6 1.6 0 000-3.2zM9.5 8.5c1-1.4 4-1.4 5 0" fill="none"/>', 'url' => '/what-we-do/social-media-content'],
                            ['label' => 'UGC-style AI Videos', 'icon' => '<rect x="6" y="2.5" width="12" height="19" rx="2"/><path d="M9.5 12l2 2 3.5-4"/>', 'url' => '/what-we-do/ugc-style-ai-videos'],
                            ['label' => 'Explainer Videos', 'icon' => '<rect x="3" y="4.5" width="18" height="13" rx="2"/><path d="M10 8.5l5 3-5 3v-6z"/><path d="M8 21h8"/>', 'url' => '/what-we-do/explainer-videos'],
                            ['label' => 'Motion Graphics', 'icon' => '<path d="M12 3l9 9-9 9-9-9z"/>', 'url' => '/what-we-do/motion-graphics'],
                            ['label' => 'Creative Concepts', 'icon' => '<path d="M9 18h6M10 21h4M8 10a4 4 0 118 0c0 2-1.5 2.8-2 4.2-.2.6-.3 1-.3 1.8h-3.4c0-.8-.1-1.2-.3-1.8C9.5 12.8 8 12 8 10z"/>', 'url' => '/what-we-do/creative-concepts'],
                            ['label' => 'Marketing Ideas', 'icon' => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="0.8" fill="currentColor"/>', 'url' => '/what-we-do/marketing-ideas'],
                            ['label' => 'Scriptwriting', 'icon' => '<path d="M6 3.5h9l3 3v14H6z"/><path d="M9 11h6M9 14.5h6M9 18h3"/>', 'url' => '/what-we-do/scriptwriting'],
                            ['label' => 'Storyboarding', 'icon' => '<rect x="3.5" y="3.5" width="7.5" height="7.5" rx="1.2"/><rect x="13" y="3.5" width="7.5" height="7.5" rx="1.2"/><rect x="3.5" y="13" width="7.5" height="7.5" rx="1.2"/><rect x="13" y="13" width="7.5" height="7.5" rx="1.2"/>', 'url' => '/what-we-do/storyboarding'],
                            ['label' => 'Video Editing', 'icon' => '<circle cx="6.5" cy="6.5" r="2.3"/><circle cx="6.5" cy="17.5" r="2.3"/><path d="M8.2 8l11.3 11M19.5 4L8.2 15.9"/>', 'url' => '/what-we-do/video-editing'],
                            ['label' => 'Content Strategy', 'icon' => '<path d="M4 20V10M11 20V4M18 20v-7"/><path d="M3 20h18"/>', 'url' => '/what-we-do/content-strategy'],
                        ] as $service)
                            <a href="{{ url($service['url']) }}" class="service-item @if(isset($loop) && $loop->index >= 8) service-item--hidden @endif">
                                <div class="service-icon">
                                    <svg viewBox="0 0 24 24">{!! $service['icon'] !!}</svg>
                                </div>
                                <p>{{ $service['label'] }}</p>
                            </a>
                        @endforeach
                    </div>
                    <div class="services-controls">
                        <button id="show-more-services" class="btn btn-outline btn-sm">Show More</button>
                    </div>
                </section>

                {{-- ============ FEATURED PROJECTS ============ --}}
                <section class="projects-section">
                    <div class="projects-header">
                        <h2>Featured Projects</h2>
                    </div>

                    @php
                        $featuredItems = isset($featuredProjects) && $featuredProjects->isNotEmpty()
                            ? $featuredProjects
                            : [
                                (object)['title' => 'Nike Inspired Commercial', 'subtitle' => 'AI Commercial Ad', 'image' => 'https://images.unsplash.com/photo-1519861531473-9200262188bf?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Luxury Perfume Ad', 'subtitle' => 'AI Product Ad', 'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Echoes of Us', 'subtitle' => 'AI Drama / Storytelling', 'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Beyond the Realm', 'subtitle' => 'AI Short Film', 'image' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Project Orion Trailer', 'subtitle' => 'AI Movie Trailer', 'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Fresh Max Campaign', 'subtitle' => 'AI Brand Campaign', 'image' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                            ];
                    @endphp

                    <div class="projects-grid">
                        @foreach ($featuredItems as $project)
                            @php
                                $projectImage = data_get($project, 'image_url') ?: data_get($project, 'image');
                                $projectVideo = data_get($project, 'video_url');
                                $categoryLabel = collect(data_get($project, 'categories', []))
                                    ->filter()
                                    ->map(fn($cat) => ucwords(str_replace('-', ' ', $cat)))
                                    ->first() ?: 'Video';
                            @endphp
                            <article class="project-card{{ $projectVideo ? ' has-video' : '' }}">
                                @php
                                    $projectSubtitle = trim((string) data_get($project, 'subtitle'));
                                    $showProjectSubtitle = $projectSubtitle !== '' && strtolower($projectSubtitle) !== 'kaaayo';
                                @endphp
                                <div class="project-thumb" @if(!$projectVideo) style="background-image:url('{{ $projectImage }}')" @endif>
                                    @if($projectVideo)
                                        <video class="project-video" autoplay muted playsinline loop preload="metadata" poster="{{ $projectImage }}">
                                            <source src="{{ $projectVideo }}" type="video/mp4" />
                                        </video>
                                        <div class="category-label">{{ $categoryLabel }}</div>
                                        <button class="mute-toggle" aria-label="Unmute">🔇</button>
                                        <button type="button" class="expand-btn" aria-label="Expand video">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                                <path d="M3 9V3h6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M21 15v6h-6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M21 3l-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M3 21l7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                                @unless($projectVideo)
                                    <h3>{{ data_get($project, 'title') }}</h3>
                                    @if($showProjectSubtitle)
                                        <p>{{ $projectSubtitle }}</p>
                                    @endif
                                @endunless
                            </article>
                        @endforeach
                    </div>
                    <div class="projects-cta">
                        <a href="{{ url('/portfolio') }}" class="btn btn-outline btn-sm">View All Projects</a>
                    </div>
                </section>

                {{-- ============ PROCESS ============ --}}
                <section class="process-section">
                    <p class="section-eyebrow">Our Process</p>
                    <h2 class="section-title">From Idea to Impact</h2>

                    <div class="process-steps">
                        @foreach ([
                            ['number' => '1', 'title' => 'Discover', 'text' => 'We learn about your brand, goals, and target audience.', 'icon' => '<path d="M21 11.5a8.4 8.4 0 01-8.6 8.4A8.6 8.6 0 014 11.3 8.4 8.4 0 0112.4 3 8.5 8.5 0 0121 11.5z"/><path d="M8 12h.01M12 12h.01M16 12h.01"/>'],
                            ['number' => '2', 'title' => 'Conceptualize', 'text' => 'We create creative concepts and strategies.', 'icon' => '<path d="M9 18h6M10 21h4M8 10a4 4 0 118 0c0 2-1.5 2.8-2 4.2-.2.6-.3 1-.3 1.8h-3.4c0-.8-.1-1.2-.3-1.8C9.5 12.8 8 12 8 10z"/>'],
                            ['number' => '3', 'title' => 'Produce', 'text' => 'We bring ideas to life with AI-powered creation.', 'icon' => '<path d="M4 20l1-4.2L15.5 5.3a1.5 1.5 0 012.1 0l1.1 1.1a1.5 1.5 0 010 2.1L8.2 19 4 20z"/>'],
                            ['number' => '4', 'title' => 'Refine', 'text' => 'We edit, refine, and make sure every detail is perfect.', 'icon' => '<rect x="3" y="5" width="18" height="12" rx="1.5"/><path d="M9 21h6M12 17v4"/><path d="M9 11l2 2 4-4"/>'],
                            ['number' => '5', 'title' => 'Deliver', 'text' => 'We deliver high-quality content that drives real impact.', 'icon' => '<path d="M12 2.5c2.5 2 4 5 4 9 0 2-.6 3.6-1.2 4.8L12 19l-2.8-2.7C8.6 15.1 8 13.5 8 11.5c0-4 1.5-7 4-9z"/><path d="M9.5 17.5l-2 3M14.5 17.5l2 3"/><circle cx="12" cy="10.5" r="1.6"/>'],
                        ] as $index => $step)
                            <div class="process-step">
                                <div class="step-icon-wrap">
                                    <div class="step-icon-box">
                                        <svg viewBox="0 0 24 24">{!! $step['icon'] !!}</svg>
                                    </div>
                                    <span class="step-number">{{ $step['number'] }}</span>
                                </div>
                                <div class="step-content">
                                    <h4>{{ $step['title'] }}</h4>
                                    <p>{{ $step['text'] }}</p>
                                </div>
                            </div>
                            @if ($index < 4)
                                <span class="step-connector"></span>
                            @endif
                        @endforeach
                    </div>
                </section>

                {{-- ============ STATS / CTA BAR ============ --}}
                <section class="stats-bar">
                    <div class="stats-bar-inner">
                        <div class="stats-grid">
                            @foreach ([
                                ['value' => '3+', 'label' => 'Years Experience'],
                                ['value' => '500M+', 'label' => 'Views Generated'],
                                ['value' => '100+', 'label' => 'Projects Completed'],
                                ['value' => '50+', 'label' => 'Happy Clients'],
                            ] as $metric)
                                <div class="stat-item">
                                    <p class="stat-value">{{ $metric['value'] }}</p>
                                    <p class="stat-label">{{ $metric['label'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="stats-cta">
                            <div>
                                <h3>Ready to bring your ideas to life?</h3>
                                <p>Let's create something amazing together.</p>
                            </div>
                        </div>
                    </div>
                </section>

                @include('partials.footer')

            </div>
        </div>
    </body>
</html>