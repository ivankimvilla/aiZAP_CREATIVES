<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Portfolio | aiZAP CREATIVES</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/pages-shared.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/css/portfolio.css', 'resources/js/pages/portfolio.js'])
            @else
                <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
                <link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}" />
                <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
                <script src="{{ asset('js/app.js') }}?v={{ filemtime(public_path('js/app.js')) }}"></script>
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
                                (object)['title' => 'Echo Spark', 'subtitle' => 'AI short film for an emerging lifestyle brand', 'image' => 'https://images.unsplash.com/photo-1519861531473-9200262188bf?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Nova Launch', 'subtitle' => 'Product launch video with high-impact visuals', 'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                                (object)['title' => 'Pulse Promo', 'subtitle' => 'Social-first campaign for new entertainment series', 'image' => 'https://images.unsplash.com/photo-1465101046530-73398c7f28ca?auto=format&fit=crop&w=700&q=80', 'video_url' => null],
                            ];

                        $collageImages = collect($portfolioItems)
                            ->map(fn($p) => data_get($p, 'image_url')
                                ?: data_get($p, 'image')
                                ?: data_get($p, 'poster')
                                ?: data_get($p, 'poster_url')
                                ?: data_get($p, 'thumbnail')
                                ?: data_get($p, 'thumbnail_url'))
                            ->filter()
                            ->values();

                        // The collage always shows 4 panels, like the reference.
                        // If there aren't 4 real project images yet, pad with a
                        // small stock set so the hero never looks sparse.
                        $collageFallbackPool = [
                            'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1446776877081-d282a0f896e2?auto=format&fit=crop&w=900&q=80',
                            'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=900&q=80',
                        ];
                        $i = 0;
                        while ($collageImages->count() < 4) {
                            $collageImages->push($collageFallbackPool[$i % count($collageFallbackPool)]);
                            $i++;
                        }
                        $collageImages = $collageImages->take(4);
                    @endphp

                    <section class="page-hero">
                        <div class="hero-copy">
                            <p class="eyebrow">Portfolio</p>
                            <h1 class="hero-title">Our <span class="gold">Work</span></h1>
                            <p class="hero-sub">Explore the AI-driven campaigns, short films, and premium visual experiences created for leading brands and startups.</p>
                        </div>

                        <div class="hero-collage-frame">
                            <div class="hero-collage">
                                @foreach ($collageImages as $image)
                                    <div class="hero-collage-item" style="background-image:url('{{ $image }}')"></div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section class="projects-section">
                        <div class="projects-intro">
                            <p class="projects-intro-label"><span class="projects-intro-label__first">Recent</span> <span class="projects-intro-label__second">Campaigns</span></p>
                            <p class="projects-intro-desc">A glimpse at the latest films, launches, and social campaigns we've brought to life for our clients.</p>
                        </div>

                        <div class="projects-grid">
                            @foreach ($portfolioItems as $project)
                                @php
                                    $projectImage = data_get($project, 'image_url') ?: data_get($project, 'image');
                                    $projectVideo = data_get($project, 'video_url');
                                    $categoryLabel = collect(data_get($project, 'categories', []))
                                        ->filter()
                                        ->map(fn($cat) => ucwords(str_replace('-', ' ', $cat)))
                                        ->first() ?: 'Video';
                                    // If visibleCategories are set, only show project videos whose categories intersect
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
                                <article class="project-card{{ $projectVideo ? ' has-video' : '' }}">
                                    <div class="project-thumb" @if(!$projectVideo) style="background-image:url('{{ $projectImage }}')" @endif>
                                        @if($projectVideo)
                                            <video class="project-video" autoplay muted playsinline loop preload="none" poster="{{ $projectImage }}" data-src="{{ $projectVideo }}">
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
                                        <p>{{ data_get($project, 'subtitle') }}</p>
                                    @endunless
                                </article>
                            @endforeach
                        </div>
                    </section>

                    <section class="stats-bar">
                        <div class="stats-bar-inner">
                            <div class="stats-grid">
                                @foreach ([
                                    ['value' => '12', 'label' => 'Campaigns Launched'],
                                    ['value' => '8', 'label' => 'Industry Categories'],
                                    ['value' => '15M', 'label' => 'Average Reach'],
                                ] as $metric)
                                    <div class="stat-item">
                                        <p class="stat-value">{{ $metric['value'] }}</p>
                                        <p class="stat-label">{{ $metric['label'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="stats-cta">
                                <div>
                                    <h3>See our work come alive.</h3>
                                    <p>Every project is designed to drive attention, emotion, and action.</p>
                                </div>
                                @include('partials.book-call-button', ['class' => 'btn btn-primary'])
                            </div>
                        </div>
                    </section>
                </main>
                @include('partials.footer')

            </div>
        </div>


    </body>
</html>