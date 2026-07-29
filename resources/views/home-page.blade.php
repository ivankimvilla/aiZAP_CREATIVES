<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Aizap Creatives Studios</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if ((file_exists(public_path('build/manifest.json')) && filesize(public_path('build/manifest.json')) > 0) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/js/pages/home.js'])
            @else
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
        @endif
    </head>
    <body class="home-page-page home-page antialiased">
        <div class="background-glow">
            <div class="container">

                @include('partials.header')

                <section class="hero-section">
                    <div class="hero-left">
                        <p class="eyebrow">We Create Your Imagination</p>
                        <h1 class="hero-title">
                            AI-Powered<br>
                            Creativity<br>
                            That <span class="gold">Builds</span> Brands.
                        </h1>
                        <p class="hero-sub">We create high-quality AI-generated videos, ads, and creative content that help brands stand out, connect, and grow in the digital world.</p>
                        <div class="hero-actions">
                            <a href="{{ url('/portfolio') }}" class="btn btn-primary">View Our Work</a>
                            @include('partials.book-call-button', ['class' => 'btn btn-outline', 'label' => 'Book A Consultation'])
                        </div>
                    </div>

                    <div class="hero-panels">
                        <div class="hero-panel-media" style="background-image:url('{{ asset('home-bg.png') }}')"></div>
                    </div>
                </section>

                <section class="services-strip">
                    <div class="services-grid">
                        @foreach ([
                            ['label' => 'AI Commercial Ads', 'icon' => '<path d="M4 9h16v10a1 1 0 01-1 1H5a1 1 0 01-1-1V9z"/><path d="M4 9l1.5-4h3L7 9M9.5 9L11 5h3l-1.5 4M15 9l1.5-4h3L18 9"/>', 'url' => '/what-we-do/ai-commercial-ads'],
                            ['label' => 'AI Product Ads', 'icon' => '<path d="M6 8V6a3 3 0 016 0v2M3.5 8h13l-1 12h-11l-1-12z"/><circle cx="17.5" cy="6.5" r="2.2"/><path d="M17.5 5.3v2.4M16.3 6.5h2.4"/>', 'url' => '/what-we-do/ai-product-ads'],
                            ['label' => 'AI Storytelling / Drama', 'icon' => '<circle cx="8.5" cy="12" r="5.5"/><circle cx="15.5" cy="12" r="5.5"/>', 'url' => '/what-we-do/ai-storytelling-drama'],
                            ['label' => 'AI Movie Trailers', 'icon' => '<rect x="6" y="2.5" width="12" height="19" rx="2"/><path d="M10 18.5h4" /><path d="M10 6h4v3h-4z"/>', 'url' => '/what-we-do/ai-movie-trailers'],
                            ['label' => 'UGC-style AI Videos', 'icon' => '<path d="M12 3a5 5 0 00-5 5v3l-2 4h14l-2-4V8a5 5 0 00-5-5z"/><path d="M9 19a3 3 0 006 0"/>', 'url' => '/what-we-do/ugc-style-ai-videos'],
                            ['label' => 'Explainer Videos', 'icon' => '<rect x="3" y="4.5" width="18" height="13" rx="2"/><path d="M10 8.5l5 3-5 3v-6z"/><path d="M8 21h8"/>', 'url' => '/what-we-do/explainer-videos'],
                        ] as $service)
                            <a href="{{ url($service['url']) }}" class="service-item">
                                <div class="service-icon">
                                    <svg viewBox="0 0 24 24">{!! $service['icon'] !!}</svg>
                                </div>
                                <p>{{ $service['label'] }}</p>
                            </a>
                        @endforeach
                    </div>
                </section>

                <section class="projects-section">
                    <div class="projects-header">
                        <div>
                            <p class="section-eyebrow" style="text-align:left;margin-bottom:6px;">Our Work</p>
                            <h2>Featured Projects</h2>
                        </div>
                        <a href="{{ url('/portfolio') }}" class="btn btn-outline btn-sm">View All Projects</a>
                    </div>

                    @php
                        $featuredItems = isset($featuredProjects) && $featuredProjects->isNotEmpty()
                            ? $featuredProjects
                            : [
                                (object)['title' => 'Nike Commercial', 'subtitle' => 'AI Commercial Ad', 'image' => 'https://images.unsplash.com/photo-1519861531473-9200262188bf?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Luxury Perfume Ad', 'subtitle' => 'AI Product Ad', 'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Echoes of Us', 'subtitle' => 'AI Drama / Storytelling', 'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Beyond the Realm', 'subtitle' => 'AI Short Film', 'image' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
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
                                $projectSubtitle = trim((string) data_get($project, 'subtitle'));
                                $showProjectSubtitle = $projectSubtitle !== '' && strtolower($projectSubtitle) !== 'kaaayo';
                            @endphp
                            <article class="project-card{{ $projectVideo ? ' has-video' : '' }}">
                                <div class="project-thumb" @if(!$projectVideo) style="background-image:url('{{ $projectImage }}')" @endif>
                                    @if($projectVideo)
                                        <video class="project-video" autoplay muted playsinline loop preload="metadata" poster="{{ $projectImage }}" data-src="{{ $projectVideo }}">
                                            <source src="{{ $projectVideo }}" type="video/mp4" />
                                        </video>
                                        <div class="category-label">{{ $categoryLabel }}</div>
                                        <button type="button" class="mute-toggle" aria-label="Unmute">🔇</button>
                                        <button type="button" class="expand-toggle" aria-label="Expand video">⛶</button>
                                    @endif
                                </div>
                                <h3>{{ data_get($project, 'title') }}</h3>
                                @if($showProjectSubtitle)
                                    <p>{{ $projectSubtitle }}</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </section>

                @include('partials.footer')

            </div>
        </div>
    </body>
</html>