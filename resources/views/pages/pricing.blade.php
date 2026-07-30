<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Pricing | Aizap Creatives Studios</title>

        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />

        @if ((file_exists(public_path('build/manifest.json')) && filesize(public_path('build/manifest.json')) > 0) || file_exists(public_path('hot')))
            @vite(['resources/css/header.css', 'resources/css/home-page.css', 'resources/css/pages-shared.css', 'resources/css/footer.css', 'resources/css/button.css', 'resources/css/process.css', 'resources/css/pricing.css', 'resources/js/pages/pricing.js'])
            @else
            <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/home-page.css') }}" />
            <script type="module" src="{{ asset('js/app.js') }}"></script>
            <link rel="stylesheet" href="{{ asset('css/pages-shared.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/process.css') }}" />
            <link rel="stylesheet" href="{{ asset('css/pricing.css') }}" />
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
                            <h1 class="hero-title">Flexible <span class="gold">Pricing</span></h1>
                            <p class="hero-sub">Every project is unique. We create custom AI videos tailored to your vision, timeline, and campaign needs.</p>
                        </div>

                        <div class="hero-media">
                            <div class="hero-panels">
                                <div class="hero-panel-media" style="background-image:url('{{ asset('home-bg.png') }}')"></div>
                            </div>
                        </div>
                    </section>

                    <section class="projects-section">
                        @php
                            /*
                            |--------------------------------------------------------------------------
                            | Plan icons
                            |--------------------------------------------------------------------------
                            | Clean 24x24 line icons, consistent 1.6 stroke weight, rounded caps/joins,
                            | so every icon reads at the same visual size/weight inside the pricing-card
                            | icon badge (see .pricing-card-icon in pricing.css).
                            */
                            $planIcons = [
                                // AI Commercial Ads — clapperboard with play triangle
                                'film' => <<<'SVG'
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
    <path d="M3 9.7 4.7 4.3a1 1 0 0 1 1.2-.7l12.9 3a1 1 0 0 1 .8 1.1l-.8 3"/>
    <path d="m8.6 4.6 2.7 4.9M14.2 5.9l2.7 4.9"/>
    <rect x="3" y="9.7" width="18" height="10" rx="1.6"/>
    <path d="M10.4 13.3v3.6l3.4-1.8z"/>
</svg>
SVG,
                                // Product Advertising — tote bag with plus
                                'bag' => <<<'SVG'
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
    <path d="M9 8V6.5a3 3 0 0 1 6 0V8"/>
    <path d="M6.4 8h11.2l-1 12.1a2 2 0 0 1-2 1.9H9.4a2 2 0 0 1-2-1.9z"/>
    <path d="M12 11.2v3.6M10.2 13h3.6"/>
</svg>
SVG,
                                // Storytelling & Short Films — comedy/drama masks
                                'masks' => <<<'SVG'
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
    <circle cx="9" cy="14" r="5.3"/>
    <circle cx="15" cy="8.5" r="5.3"/>
    <circle cx="7.3" cy="12.8" r=".55" fill="currentColor" stroke="none"/>
    <circle cx="10.7" cy="12.8" r=".55" fill="currentColor" stroke="none"/>
    <path d="M7.3 15q1.7 1.6 3.4 0"/>
    <circle cx="13.3" cy="7.3" r=".55" fill="currentColor" stroke="none"/>
    <circle cx="16.7" cy="7.3" r=".55" fill="currentColor" stroke="none"/>
    <path d="M13.3 10.3q1.7 -1.6 3.4 0"/>
</svg>
SVG,
                                // Custom Projects — diagonal pencil
                                'pencil' => <<<'SVG'
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
    <path d="m14.6 3.9 5.5 5.5L9.4 20.1H3.9v-5.5z"/>
    <path d="m12.8 5.7 5.5 5.5"/>
</svg>
SVG,
                            ];

                            $plans = [
                                [
                                    'title' => 'AI Commercial Ads',
                                    'title_top' => 'AI Commercial',
                                    'title_bottom' => 'Ads',
                                    'subtitle' => 'High-converting cinematic advertisements for brands.',
                                    'items' => ['30-60 sec AI commercial', 'Script assistance', 'Cinematic quality', 'Fast turnaround', 'Multiple formats', 'Commercial use'],
                                    'cta' => 'Request A Quote',
                                    'icon' => $planIcons['film'],
                                ],
                                [
                                    'title' => 'Product Advertising',
                                    'title_top' => 'Product',
                                    'title_bottom' => 'Advertising',
                                    'subtitle' => 'Showcase your product with premium AI visuals.',
                                    'items' => ['Product-focused videos', 'Social media ready', 'Multiple aspect ratios', 'High-quality visuals', 'Engaging storytelling', 'Commercial use'],
                                    'cta' => 'Request A Quote',
                                    'icon' => $planIcons['bag'],
                                ],
                                [
                                    'title' => 'Storytelling & Short Films',
                                    'title_top' => 'Storytelling &',
                                    'title_bottom' => 'Short Films',
                                    'subtitle' => 'Emotional AI films that connect with audiences.',
                                    'items' => ['Story development', 'Cinematic scenes', 'Character consistency', 'Creative direction', 'Background score', 'Multiple revisions'],
                                    'cta' => 'Request A Quote',
                                    'icon' => $planIcons['masks'],
                                ],
                                [
                                    'title' => 'Custom Projects',
                                    'title_top' => 'Custom',
                                    'title_bottom' => 'Projects',
                                    'subtitle' => "Need something unique? We'll build it together.",
                                    'items' => ['Brand campaigns', 'Music videos', 'Explainer videos', 'Social media content', 'Creative concepts', 'And more'],
                                    'cta' => "Let's Talk",
                                    'icon' => $planIcons['pencil'],
                                ],
                            ];

                            $plansAssoc = collect($plans)->keyBy('title')->toArray();
                        @endphp

                        <div class="pricing-grid">
                            @foreach ($plans as $plan)
                                <article class="pricing-card">
                                    <span class="pricing-card-icon">{!! $plan['icon'] !!}</span>
                                    <h3 class="pricing-card-title">
                                        <span>{{ $plan['title_top'] }}</span>
                                        <span class="gold">{{ $plan['title_bottom'] }}</span>
                                    </h3>
                                    <p>{{ $plan['subtitle'] }}</p>
                                    <div class="pricing-card-divider"></div>
                                    <ul class="pricing-list">
                                        @foreach ($plan['items'] as $line)
                                            <li>{{ $line }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn btn-outline pricing-select-button" data-plan="{{ $plan['title'] }}" aria-pressed="false">
                                        {{ $plan['cta'] }}
                                        <span class="icon-arrow" aria-hidden="true">&#8599;</span>
                                    </button>
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
                                                <p class="summary-subtitle">@if(old('package') && isset($plansAssoc[old('package')])) {{ $plansAssoc[old('package')]['subtitle'] }} @else Submit your package request and we'll respond with pricing details. @endif</p>
                                            </div>

                                            <div class="pricing-summary-body">
                                                <p class="summary-note">We'll review your needs and reply with a custom quote and next steps.</p>
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

                                        <label class="field field-company">
                                            <span class="label-text">Company / Brand</span>
                                            <input type="text" name="company" placeholder="Your company" value="{{ old('company') }}" />
                                        </label>

                                        <label class="field field-phone">
                                            <span class="label-text">Phone</span>
                                            <input type="text" name="phone" placeholder="Your phone number" value="{{ old('phone') }}" />
                                        </label>

                                        <label class="field field-message">
                                            <span class="label-text">Tell us about your project</span>
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

                    @php
                        $perkIcons = [
                            'bolt' => <<<'SVG'
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 4.5 14h6L9.5 22 19.5 9h-6z"/></svg>
SVG,
                            'diamond' => <<<'SVG'
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6.3 3h11.4l4.3 6-10 12-10-12z"/><path d="M2.6 9h18.8M9.3 3 7 9l5 12 5-12-2.3-6"/></svg>
SVG,
                            'shield' => <<<'SVG'
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3 5 6v6c0 4.6 3 8.3 7 9.3 4-1 7-4.7 7-9.3V6z"/><path d="m9 12.2 2 2 4-4.2"/></svg>
SVG,
                            'headset' => <<<'SVG'
<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 13v-1.5a8 8 0 0 1 16 0V13"/><rect x="2.3" y="13" width="4.4" height="6.4" rx="1.6"/><rect x="17.3" y="13" width="4.4" height="6.4" rx="1.6"/><path d="M20 19.4v.8a2 2 0 0 1-2 2h-3.6"/></svg>
SVG,
                        ];

                        $perks = [
                            ['icon' => $perkIcons['bolt'], 'title' => 'Fast Turnaround', 'text' => 'Quick delivery without compromising quality.'],
                            ['icon' => $perkIcons['diamond'], 'title' => 'Premium Quality', 'text' => 'Cinematic AI visuals that stand out.'],
                            ['icon' => $perkIcons['shield'], 'title' => 'Commercial Use', 'text' => '100% safe for your brand and business.'],
                            ['icon' => $perkIcons['headset'], 'title' => 'Dedicated Support', 'text' => "We're with you from concept to delivery."],
                        ];
                    @endphp

                    <section class="pricing-perks-bar">
                        <div class="perks-bar-inner">
                            <div class="perks-grid">
                                @foreach ($perks as $perk)
                                    <div class="perk-item">
                                        <span class="perk-icon">{!! $perk['icon'] !!}</span>
                                        <div class="perk-copy">
                                            <h3>{{ $perk['title'] }}</h3>
                                            <p>{{ $perk['text'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="perks-cta">
                                @include('partials.book-call-button', ['class' => 'btn btn-outline', 'label' => 'Book A Consultation'])
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