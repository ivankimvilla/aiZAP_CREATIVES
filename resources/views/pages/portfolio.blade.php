<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Portfolio | Aizap Creatives Studios</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if ((file_exists(public_path('build/manifest.json')) && filesize(public_path('build/manifest.json')) > 0) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/pages-shared.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/css/portfolio.css', 'resources/js/pages/portfolio.js'])
            @else
                <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
                <link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}" />
                <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
                <script type="module" src="{{ asset('js/app.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('css/pages-shared.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/portfolio.css') }}" />
             <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/button.css') }}" />
        @endif
    </head>
    <body class="home-page-page portfolio-page antialiased">
        <div class="background-glow">
            <div class="container">
                @include('partials.header')

                <main class="page-main">
                    @php
                        $portfolioItems = isset($projects) && $projects->isNotEmpty()
                            ? $projects
                            : [
                                (object)['title' => 'Nike Commercial', 'subtitle' => 'AI Commercial Ad', 'image' => 'https://images.unsplash.com/photo-1519861531473-9200262188bf?auto=format&fit=crop&w=700&q=80', 'video_url' => null, 'duration' => '0:30'],
                                (object)['title' => 'Lumière Perfume Ad', 'subtitle' => 'AI Product Ad', 'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=700&q=80', 'video_url' => null, 'duration' => '0:15'],
                                (object)['title' => 'Echoes of Us', 'subtitle' => 'AI Drama / Storytelling', 'image' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=700&q=80', 'video_url' => null, 'duration' => '1:02'],
                            ];
                    @endphp

                    <section class="pf-hero">
                        <div class="pf-hero-copy">
                            <p class="eyebrow">Our Portfolio</p>
                            <h1 class="hero-title">Creative Work.<br><span class="gold">Real Impact.</span></h1>
                            <p class="hero-sub">Explore a selection of AI-powered videos, ads, and content we've created for brands, businesses, and agencies around the world.</p>
                        </div>

                        <div class="pf-hero-media" style="background-image:url('{{ asset('home-bg.png') }}')"></div>
                    </section>

                    <section class="services-strip" id="projects">
                        <div class="services-grid">
                            <span class="service-item is-active is-static">
                                <p>All Projects</p>
                            </span>

                            @foreach ([
                                ['label' => 'AI Commercial Ads', 'icon' => '<path d="M4 9h16v10a1 1 0 01-1 1H5a1 1 0 01-1-1V9z"/><path d="M4 9l1.5-4h3L7 9M9.5 9L11 5h3l-1.5 4M15 9l1.5-4h3L18 9"/>', 'url' => '/what-we-do/ai-commercial-ads'],
                                ['label' => 'AI Product Ads', 'icon' => '<path d="M6 8V6a3 3 0 016 0v2M3.5 8h13l-1 12h-11l-1-12z"/><circle cx="17.5" cy="6.5" r="2.2"/><path d="M17.5 5.3v2.4M16.3 6.5h2.4"/>', 'url' => '/what-we-do/ai-product-ads'],
                                ['label' => 'AI Drama', 'icon' => '<circle cx="8.5" cy="12" r="5.5"/><circle cx="15.5" cy="12" r="5.5"/>', 'url' => '/what-we-do/ai-storytelling-drama'],
                                ['label' => 'AI Movie Trailers', 'icon' => '<rect x="6" y="2.5" width="12" height="19" rx="2"/><path d="M10 18.5h4" /><path d="M10 6h4v3h-4z"/>', 'url' => '/what-we-do/ai-movie-trailers'],
                                ['label' => 'UGC-style AI Videos', 'icon' => '<path d="M12 3a5 5 0 00-5 5v3l-2 4h14l-2-4V8a5 5 0 00-5-5z"/><path d="M9 19a3 3 0 006 0"/>', 'url' => '/what-we-do/ugc-style-ai-videos'],
                                ['label' => 'Explainer Videos', 'icon' => '<rect x="3" y="4.5" width="18" height="13" rx="2"/><path d="M10 8.5l5 3-5 3v-6z"/><path d="M8 21h8"/>', 'url' => '/what-we-do/explainer-videos'],
                            ] as $service)
                                <a href="{{ url($service['url']) }}" class="service-item{{ request()->is(ltrim($service['url'], '/')) ? ' is-active' : '' }}">
                                    <span class="service-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $service['icon'] !!}</svg>
                                    </span>
                                    <p>{{ $service['label'] }}</p>
                                </a>
                            @endforeach
                        </div>
                    </section>

                    <section class="pf-projects-section">
                        <div class="pf-projects-grid">
                            @foreach ($portfolioItems as $project)
                                @php
                                    $projectImage = data_get($project, 'image_url') ?: data_get($project, 'image');
                                    $projectVideo = data_get($project, 'video_url');
                                    $projectDuration = data_get($project, 'duration');
                                    $categoryLabel = collect(data_get($project, 'categories', []))
                                        ->filter()
                                        ->map(fn($cat) => ucwords(str_replace('-', ' ', $cat)))
                                        ->first() ?: data_get($project, 'subtitle', 'Video');

                                    if (isset($visibleCategories) && is_array($visibleCategories) && count($visibleCategories) > 0) {
                                        $normalize = function ($val) {
                                            $s = is_string($val) ? $val : '';
                                            $s = trim(strtolower($s));
                                            $s = preg_replace('/[^a-z0-9\s-]+/', '', $s);
                                            $s = preg_replace('/\s+/', '-', $s);
                                            return $s;
                                        };
                                        $visibleSlugs = array_map($normalize, $visibleCategories);

                                        $projCats = (array) data_get($project, 'categories', []);
                                        $allowed = false;
                                        foreach ($projCats as $c) {
                                            if (in_array($normalize($c), $visibleSlugs)) { $allowed = true; break; }
                                        }
                                        if (!$allowed) {
                                            $projectVideo = null;
                                        }
                                    }
                                @endphp
                                <article class="pf-project-card{{ $projectVideo ? ' has-video' : '' }}">
                                    <div class="pf-project-thumb" style="background-image:url('{{ $projectImage }}')">
                                        <span class="pf-thumb-overlay"></span>

                                        @if ($projectVideo)
                                            <video class="pf-project-video" autoplay muted playsinline loop preload="metadata" poster="{{ $projectImage }}" src="{{ $projectVideo }}" data-src="{{ preg_match('/^\/\//', $projectVideo) ? 'https:'.$projectVideo : $projectVideo }}">
                                                <source src="{{ $projectVideo }}" type="video/mp4" />
                                            </video>
                                            <button type="button" class="pf-expand-toggle expand-toggle" aria-label="Expand video">⛶</button>
                                            <button type="button" class="pf-mute-toggle mute-toggle" aria-label="Unmute">🔇</button>
                                        @endif

                                        @if ($projectDuration)
                                            <span class="pf-duration-badge">{{ $projectDuration }}</span>
                                        @endif
                                    </div>
                                    @if ($projectVideo)
                                        <button type="button" class="pf-video-close" aria-label="Close video">×</button>
                                    @endif

                                    <div class="pf-project-card-info">
                                        <div>
                                            <h3>{{ Str::upper(data_get($project, 'title')) }}</h3>
                                            <p>{{ $categoryLabel }}</p>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <section class="pf-bottom-cta">
                        <span class="pf-bottom-cta-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4.5 16.5c-1.5 1.26-2 5-2 5s3.74-.5 5-2c.71-.84.7-2.13-.09-2.91a2.18 2.18 0 0 0-2.91-.09z"/>
                                <path d="m12 15-3-3a22 22 0 0 1 2-3.95A12.88 12.88 0 0 1 22 2c0 2.72-.78 7.5-6 11a22.35 22.35 0 0 1-4 2z"/>
                                <path d="M9 12H4s.55-3.03 2-4c1.62-1.08 5 0 5 0"/>
                                <path d="M12 15v5s3.03-.55 4-2c1.08-1.62 0-5 0-5"/>
                            </svg>
                        </span>
                        <div class="pf-bottom-cta-copy">
                            <h3>Have a project in mind?</h3>
                            <p>Let's create something extraordinary together.</p>
                        </div>
                        <button type="button" class="btn btn-gold contact-toggle" aria-label="Open contact form">
                            Let's Work Together
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>
                            </svg>
                        </button>
                    </section>
                </main>
                @include('partials.footer')

            </div>
        </div>
    </body>
</html>