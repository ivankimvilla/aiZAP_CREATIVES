<header class="site-header">
    <a href="{{ url('/') }}" class="site-header__brand" aria-label="Home">
        <img src="{{ asset('logo.png') }}" alt="aiZAP Creatives logo" />
    </a>

    <nav class="site-header__nav">
        <a href="{{ url('/') }}" class="site-header__nav-link {{ request()->is('/') ? 'site-header__nav-link--active' : '' }}">Home</a>
        <a href="{{ url('/about-us') }}" class="site-header__nav-link {{ request()->is('about-us') ? 'site-header__nav-link--active' : '' }}">About Us</a>
        <a href="{{ url('/services') }}" class="site-header__nav-link {{ request()->is('services') ? 'site-header__nav-link--active' : '' }}">Services</a>
        <a href="{{ url('/portfolio') }}" class="site-header__nav-link {{ request()->is('portfolio') ? 'site-header__nav-link--active' : '' }}">Portfolio</a>
        <a href="{{ url('/process') }}" class="site-header__nav-link {{ request()->is('process') ? 'site-header__nav-link--active' : '' }}">Process</a>
        <a href="{{ url('/pricing') }}" class="site-header__nav-link {{ request()->is('pricing') ? 'site-header__nav-link--active' : '' }}">Pricing</a>
    </nav>
</header>

@include('partials.contact-dropdown')
@include('partials.booking-calendar')