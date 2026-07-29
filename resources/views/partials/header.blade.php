<link rel="stylesheet" href="{{ asset('css/header.css') }}" />
<header class="site-header">
    <a href="{{ url('/') }}" class="site-header__brand" aria-label="Home">
        <img src="{{ asset('logo.png') }}" alt="aiZAP Creatives logo" />
    </a>

    <nav class="site-header__nav" id="site-nav">
        <a href="{{ url('/') }}" class="site-header__nav-link {{ request()->is('/') ? 'site-header__nav-link--active' : '' }}">Home</a>
        <a href="{{ url('/about-us') }}" class="site-header__nav-link {{ request()->is('about-us') ? 'site-header__nav-link--active' : '' }}">About Us</a>
        <a href="{{ url('/services') }}" class="site-header__nav-link {{ request()->is('services') ? 'site-header__nav-link--active' : '' }}">Services</a>
        <a href="{{ url('/portfolio') }}" class="site-header__nav-link {{ request()->is('portfolio') ? 'site-header__nav-link--active' : '' }}">Portfolio</a>
        <a href="{{ url('/process') }}" class="site-header__nav-link {{ request()->is('process') ? 'site-header__nav-link--active' : '' }}">Process</a>
        <a href="{{ url('/pricing') }}" class="site-header__nav-link {{ request()->is('pricing') ? 'site-header__nav-link--active' : '' }}">Pricing</a>

        <button type="button" class="contact-float-button site-header__nav-contact" id="mobileContactTrigger" aria-label="Open contact form">
            Contact Us
        </button>
    </nav>

    <button type="button" class="site-header__toggle" id="site-nav-toggle"
            aria-label="Toggle navigation menu"
            aria-expanded="false"
            aria-controls="site-nav">
        <span></span>
        <span></span>
        <span></span>
    </button>
</header>

@include('partials.contact-dropdown')
@include('partials.booking-calendar')

<script>
    (function () {
        var toggle = document.getElementById('site-nav-toggle');
        var nav = document.getElementById('site-nav');

        if (!toggle || !nav) return;

        toggle.addEventListener('click', function () {
            var isOpen = nav.classList.toggle('site-header__nav--open');
            toggle.classList.toggle('site-header__toggle--open', isOpen);
            toggle.setAttribute('aria-expanded', String(isOpen));
        });

        nav.querySelectorAll('.site-header__nav-link').forEach(function (link) {
            link.addEventListener('click', function () {
                nav.classList.remove('site-header__nav--open');
                toggle.classList.remove('site-header__toggle--open');
                toggle.setAttribute('aria-expanded', 'false');
            });
        });
    })();

    // The mobile nav has its own "Contact Us" button (site-header__nav-contact)
    // that is a plain visual proxy. Clicking it forwards a real click event to
    // the actual .contact-toggle button, which stays exactly where it always
    // was — so whatever existing code opens the contact dropdown keeps working
    // untouched, instead of breaking because the real button got moved.
    (function () {
        var nav = document.getElementById('site-nav');
        var proxyButton = document.getElementById('mobileContactTrigger');
        var realButton = document.querySelector('.contact-dropdown-component .contact-toggle');

        if (!proxyButton || !realButton) return;

        proxyButton.addEventListener('click', function () {
            realButton.click();

            if (nav) {
                nav.classList.remove('site-header__nav--open');
            }
            var toggle = document.getElementById('site-nav-toggle');
            if (toggle) {
                toggle.classList.remove('site-header__toggle--open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    })();

    // Keep aria-hidden on the contact dropdown in sync with whether it's
    // actually visible (.open class), no matter what other code toggles
    // that class. Also blur any focused element inside it before hiding,
    // since browsers block/flag aria-hidden="true" while a descendant
    // still holds focus (this was firing an a11y console violation).
    (function () {
        var dropdown = document.getElementById('contactDropdown');
        if (!dropdown) return;

        function syncAriaHidden() {
            var isOpen = dropdown.classList.contains('open');

            if (!isOpen && dropdown.contains(document.activeElement)) {
                document.activeElement.blur();
            }

            dropdown.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
        }

        new MutationObserver(syncAriaHidden).observe(dropdown, {
            attributes: true,
            attributeFilter: ['class']
        });

        syncAriaHidden();
    })();
</script>