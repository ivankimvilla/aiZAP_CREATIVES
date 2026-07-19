<section class="services-strip">
    <p class="section-eyebrow">What We Do</p>
    <h2 class="section-title">Explore Our Services</h2>

    @php $currentUrl = url()->current(); @endphp

    <div class="service-nav-grid">
        @foreach ([
            ['label' => 'AI Commercial Ads', 'url' => '/what-we-do/ai-commercial-ads', 'icon' => '<path d="M4 9h16v10a1 1 0 01-1 1H5a1 1 0 01-1-1V9z"/><path d="M4 9l1.5-4h3L7 9M9.5 9L11 5h3l-1.5 4M15 9l1.5-4h3L18 9"/>'],
            ['label' => 'AI Product Ads', 'url' => '/what-we-do/ai-product-ads', 'icon' => '<path d="M6 8V6a3 3 0 016 0v2M3.5 8h13l-1 12h-11l-1-12z"/><circle cx="17.5" cy="6.5" r="2.2"/><path d="M17.5 5.3v2.4M16.3 6.5h2.4"/>'],
            ['label' => 'AI Storytelling / Drama', 'url' => '/what-we-do/ai-storytelling-drama', 'icon' => '<circle cx="8.5" cy="12" r="5.5"/><circle cx="15.5" cy="12" r="5.5"/>'],
            ['label' => 'AI Short Films', 'url' => '/what-we-do/ai-short-films', 'icon' => '<rect x="3" y="7" width="12" height="10" rx="1.5"/><path d="M15 10l6-3v10l-6-3z"/>'],
            ['label' => 'AI Movie Trailers', 'url' => '/what-we-do/ai-movie-trailers', 'icon' => '<rect x="6" y="2.5" width="12" height="19" rx="2"/><path d="M10 18.5h4" /><path d="M10 6h4v3h-4z"/>'],
            ['label' => 'AI Brand Campaigns', 'url' => '/what-we-do/ai-brand-campaigns', 'icon' => '<path d="M3 11v2a1 1 0 001 1h2l4 4V6L6 10H4a1 1 0 00-1 1z"/><path d="M15 9a3 3 0 010 6M17.5 6.5a7 7 0 010 11"/>'],
            ['label' => 'Social Media Content', 'url' => '/what-we-do/social-media-content', 'icon' => '<rect x="6" y="2.5" width="12" height="19" rx="2"/><path d="M12 16.5a1.6 1.6 0 100 3.2 1.6 1.6 0 000-3.2zM9.5 8.5c1-1.4 4-1.4 5 0" fill="none"/>'],
            ['label' => 'UGC-style AI Videos', 'url' => '/what-we-do/ugc-style-ai-videos', 'icon' => '<rect x="6" y="2.5" width="12" height="19" rx="2"/><path d="M9.5 12l2 2 3.5-4"/>'],
            ['label' => 'Explainer Videos', 'url' => '/what-we-do/explainer-videos', 'icon' => '<rect x="3" y="4.5" width="18" height="13" rx="2"/><path d="M10 8.5l5 3-5 3v-6z"/><path d="M8 21h8"/>'],
            ['label' => 'Motion Graphics', 'url' => '/what-we-do/motion-graphics', 'icon' => '<path d="M12 3l9 9-9 9-9-9z"/>'],
            ['label' => 'Creative Concepts', 'url' => '/what-we-do/creative-concepts', 'icon' => '<path d="M9 18h6M10 21h4M8 10a4 4 0 118 0c0 2-1.5 2.8-2 4.2-.2.6-.3 1-.3 1.8h-3.4c0-.8-.1-1.2-.3-1.8C9.5 12.8 8 12 8 10z"/>'],
            ['label' => 'Marketing Ideas', 'url' => '/what-we-do/marketing-ideas', 'icon' => '<circle cx="12" cy="12" r="8.5"/><circle cx="12" cy="12" r="4.5"/><circle cx="12" cy="12" r="0.8" fill="currentColor"/>'],
            ['label' => 'Scriptwriting', 'url' => '/what-we-do/scriptwriting', 'icon' => '<path d="M6 3.5h9l3 3v14H6z"/><path d="M9 11h6M9 14.5h6M9 18h3"/>'],
            ['label' => 'Storyboarding', 'url' => '/what-we-do/storyboarding', 'icon' => '<rect x="3.5" y="3.5" width="7.5" height="7.5" rx="1.2"/><rect x="13" y="3.5" width="7.5" height="7.5" rx="1.2"/><rect x="3.5" y="13" width="7.5" height="7.5" rx="1.2"/><rect x="13" y="13" width="7.5" height="7.5" rx="1.2"/>'],
            ['label' => 'Video Editing', 'url' => '/what-we-do/video-editing', 'icon' => '<circle cx="6.5" cy="6.5" r="2.3"/><circle cx="6.5" cy="17.5" r="2.3"/><path d="M8.2 8l11.3 11M19.5 4L8.2 15.9"/>'],
            ['label' => 'Content Strategy', 'url' => '/what-we-do/content-strategy', 'icon' => '<path d="M4 20V10M11 20V4M18 20v-7"/><path d="M3 20h18"/>'],
        ] as $service)
            @if ($currentUrl === url($service['url']))
                <span class="service-item service-item--active" aria-current="page">{{ $service['label'] }}</span>
            @else
                <a href="{{ url($service['url']) }}" class="service-item">{{ $service['label'] }}</a>
            @endif
        @endforeach
    </div>
</section>
