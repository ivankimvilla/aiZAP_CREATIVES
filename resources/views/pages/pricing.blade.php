<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Pricing | aiZAP CREATIVES</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/pages-shared.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/css/process.css', 'resources/css/pricing.css', 'resources/js/pages/pricing.js'])
            @else
            <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('css/pages-shared.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/process.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/pricing.css') }}" />
             <link rel="stylesheet" href="{{ asset('css/footer.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/button.css') }}" />
        @endif
    </head>
    <body class="home-page-page pricing-page antialiased">
        <div class="background-glow">
            <div class="container">
                @include('partials.header')

                <main class="page-main">
                    <section class="page-hero">
                        <div class="hero-copy">
                            <p class="eyebrow">Pricing</p>
                            <h1 class="hero-title">Flexible <span class="gold">Plans</span></h1>
                            <p class="hero-sub">Explore our tailored pricing options for AI-powered campaigns, creative production, and ongoing content strategy.</p>
                        </div>

                        <div class="hero-media">
                            <div class="process-media-label">
                                <span>Pick Your Package</span>
                            </div>

                            @php
                                $pricingGallery = [
                                    ['image' => 'https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&fit=crop&w=600&q=80', 'number' => '01', 'caption' => 'Starter', 'text' => 'A focused kickoff for a single campaign.'],
                                    ['image' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=600&q=80', 'number' => '02', 'caption' => 'Growth', 'text' => 'Ongoing production for scaling brands.'],
                                    ['image' => 'https://images.unsplash.com/photo-1573497491208-6b1acb260507?auto=format&fit=crop&w=600&q=80', 'number' => '03', 'caption' => 'Premium', 'text' => 'Full-scale support for major launches.'],
                                    ['image' => 'https://images.unsplash.com/photo-1553877522-43269d4ea984?auto=format&fit=crop&w=600&q=80', 'number' => '04', 'caption' => 'Custom', 'text' => 'Pricing shaped around your roadmap.'],
                                    ['image' => 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=600&q=80', 'number' => '05', 'caption' => 'Support', 'text' => 'Fast, dedicated help along the way.'],
                                ];
                            @endphp

                            <div class="process-scroll-wrap">
                                <div class="process-scroll">
                                    @foreach ($pricingGallery as $shot)
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

                    <section class="projects-section">
                        <div class="pricing-heading-glow">
                            <p class="section-eyebrow">Choose A Plan</p>
                            <h2 class="section-title">Pricing built around your campaign size</h2>
                        </div>

                        <div class="pricing-grid">
                            @php
                                $plans = [
                                    ['title' => 'Starter', 'subtitle' => 'For small brands and launch campaigns', 'items' => ['1 concept', '1 AI video', '1 platform-ready format', '2 revisions'], 'featured' => false],
                                    ['title' => 'Growth', 'subtitle' => 'For growing brands and recurring campaigns', 'items' => ['3 concepts', '2 AI videos', 'Multi-format delivery', '4 revisions'], 'featured' => true],
                                    ['title' => 'Premium', 'subtitle' => 'For full-scale product launches', 'items' => ['5 concepts', '5 AI videos', 'Campaign asset suite', 'Unlimited revisions'], 'featured' => false],
                                ];
                                $plansAssoc = collect($plans)->keyBy('title')->map(function($v){ return $v; })->toArray();
                            @endphp

                            @foreach ($plans as $plan)
                                <article class="pricing-card {{ $plan['featured'] ? 'featured' : '' }}">
                                    @if ($plan['featured'])
                                        <span class="pricing-badge">Most Popular</span>
                                    @endif
                                    <h3>{{ $plan['title'] }}</h3>
                                    <p>{{ $plan['subtitle'] }}</p>
                                    <ul class="pricing-list">
                                        @foreach ($plan['items'] as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn btn-outline pricing-select-button" data-plan="{{ $plan['title'] }}" aria-pressed="false">Select</button>
                                </article>
                            @endforeach
                        </div>

                            <div id="pricingOverlay" class="pricing-overlay" data-server-open="{{ old('package') || session('pricing_status') ? '1' : '0' }}" data-initial-package="{{ old('package') ?: '' }}" data-plans='@json($plansAssoc)' @if(!(old('package') || session('pricing_status'))) hidden @endif>
                                <div id="pricingInquiryPanel" class="pricing-inquiry-panel @if(old('package') || session('pricing_status')) is-open @endif" role="dialog" aria-modal="true" aria-labelledby="pricingPanelTitle">
                                    <button type="button" class="pricing-close" aria-label="Close pricing form">×</button>
                                    <div class="pricing-inquiry-content">
                                        <div class="pricing-summary">
                                            <div class="pricing-inquiry-header">
                                                <p class="section-eyebrow pricing-selected-label">Selected Package</p>
                                                <h2 class="section-title">{{ old('package') ?: 'Choose a package to get started' }}</h2>
                                                <p class="summary-subtitle">@if(old('package') && isset($plansAssoc[old('package')])) {{ $plansAssoc[old('package')]['subtitle'] }} @else Submit your package request and we’ll respond with pricing details. @endif</p>
                                            </div>

                                            <div class="pricing-summary-body">
                                                <p class="summary-note">We’ll review your needs and reply with a custom quote and next steps.</p>
                                                <ul class="summary-features">
                                                    @if(old('package') && isset($plansAssoc[old('package')]))
                                                        @foreach($plansAssoc[old('package')]['items'] as $feat)
                                                            <li>{{ $feat }}</li>
                                                        @endforeach
                                                    @else
                                                        <li>Concepts & AI video production</li>
                                                        <li>Platform-ready formats</li>
                                                        <li>Revisions & delivery timeline</li>
                                                    @endif
                                                </ul>
                                                <div class="summary-cta">
                                                    <button class="btn btn-outline pricing-summary-cancel" type="button">Change selection</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pricing-form-wrap">
                                            {{-- Server-rendered fallback messages (shown on a normal, non-JS page reload) --}}
                                            @if(session('pricing_status'))
                                                <div class="pricing-form-success">
                                                    {{ session('pricing_status') }}
                                                </div>
                                            @endif

                                            @if ($errors->any())
                                                <div class="pricing-form-errors">
                                                    <p>Please fix the following:</p>
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif

                                            {{-- AJAX-driven message containers. Hidden by default, populated/shown by JS
                                                 so the panel never has to navigate (and therefore never closes) on submit. --}}
                                            <div id="pricingAjaxSuccess" class="pricing-form-success" hidden></div>
                                            <div id="pricingAjaxErrors" class="pricing-form-errors" hidden>
                                                <p>Please fix the following:</p>
                                                <ul id="pricingAjaxErrorsList"></ul>
                                            </div>

                                            <form id="pricingInquiryForm" class="pricing-inquiry-form" action="{{ route('packages.store') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="package" value="{{ old('package') }}" />
                                    <div class="pricing-form-grid">
                                        <label class="field field-name">
                                            <span class="label-text">Name</span>
                                            <input type="text" name="name" placeholder="Your name" value="{{ old('name') }}" required />
                                        </label>

                                        <label class="field field-email">
                                            <span class="label-text">Email</span>
                                            <input type="email" name="email" placeholder="Your email" value="{{ old('email') }}" required />
                                        </label>

                                        <label class="field field-phone">
                                            <span class="label-text">Phone</span>
                                            <input type="text" name="phone" placeholder="Your phone number" value="{{ old('phone') }}" />
                                        </label>

                                        <label class="field field-message">
                                            <span class="label-text">Tell us about your campaign</span>
                                            <textarea name="message" rows="6" placeholder="Describe your goals, timeline, and any details." required>{{ old('message') }}</textarea>
                                        </label>

                                        <div class="field field-submit">
                                            <button type="submit" class="btn btn-primary">Request Pricing</button>
                                        </div>
                                    </div>
                                </form>
                                    </div>
                                </div>
                            </div>
                    </section>

                    <section class="stats-bar">
                        <div class="stats-bar-inner">
                            <div class="stats-grid">
                                @foreach ([
                                    ['value' => '3', 'label' => 'Package tiers'],
                                    ['value' => '24h', 'label' => 'Fast response'],
                                    ['value' => '100%', 'label' => 'Custom support available'],
                                ] as $metric)
                                    <div class="stat-item">
                                        <p class="stat-value">{{ $metric['value'] }}</p>
                                        <p class="stat-label">{{ $metric['label'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="stats-cta">
                                <div>
                                    <h3>Need a custom plan?</h3>
                                    <p>We tailor pricing to your brand, timeline, and creative goals.</p>
                                </div>
                            </div>
                        </div>
                    </section>
                </main>

                @if(session('pricing_status'))
                    <div id="pricingSuccessToast" class="pricing-toast" role="status" aria-live="polite">
                        <span class="pricing-toast-icon">&#10003;</span>
                        <div>
                            <strong>Success!</strong>
                            <p>{{ session('pricing_status') }}</p>
                        </div>
                    </div>
                @endif

                @include('partials.footer')

            </div>
        </div>
    </body>
</html>