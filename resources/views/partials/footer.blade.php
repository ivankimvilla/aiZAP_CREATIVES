<footer class="site-footer">
    <div class="footer-grid">
        <div>
            <div class="footer-logo"><span class="gold">ai</span>ZAP CREATIVES</div>
            <p class="footer-desc">We are an AI-powered creative studio that helps brands and creators tell their stories through next-generation content.</p>
        </div>

        <div>
            <p class="footer-heading">Quick Links</p>
            <div class="footer-links-split">
                <ul>
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/about-us') }}">About Us</a></li>
                    <li><a href="{{ url('/services') }}">Services</a></li>
                    <li><a href="{{ url('/portfolio') }}">Portfolio</a></li>
                </ul>
                <ul>
                    <li><a href="{{ url('/process') }}">Process</a></li>
                    <li><a href="{{ url('/pricing') }}">Pricing</a></li>
                </ul>
            </div>
        </div>

        <div>
            <p class="footer-heading">Services</p>
            <div class="footer-links-split">
                <ul>
                    <li><a href="{{ url('/what-we-do/ai-commercial-ads') }}">AI Commercial Ads</a></li>
                    <li><a href="{{ url('/what-we-do/ai-product-ads') }}">AI Product Ads</a></li>
                    <li><a href="{{ url('/what-we-do/ai-storytelling-drama') }}">AI Storytelling / Drama</a></li>
                    <li><a href="{{ url('/what-we-do/ai-short-films') }}">AI Short Films</a></li>
                </ul>
                <ul>
                    <li><a href="{{ url('/what-we-do/ai-movie-trailers') }}">AI Movie Trailers</a></li>
                    <li><a href="{{ url('/what-we-do/ai-brand-campaigns') }}">Brand Campaigns</a></li>
                    <li><a href="{{ url('/what-we-do/social-media-content') }}">Social Media Content</a></li>
                    <li><a href="{{ url('/services') }}">Show all services</a></li>
                </ul>
            </div>
        </div>

        <div>
            <p class="footer-heading">Get in Touch</p>
            <div class="footer-contact">
                <p>hello@aicreatives.studio</p>
                <p>+63 912 345 6789</p>
                <p>Manila, Philippines</p>
            </div>
            <div class="footer-social">
                <a href="#" class="social-icon fb" aria-label="Facebook">
                    <svg viewBox="0 0 24 24">
                        <path d="M14.5 21v-7.2h2.4l.4-2.8h-2.8v-1.8c0-.8.2-1.4 1.4-1.4h1.5V5.3c-.3 0-1.1-.1-2.1-.1-2.1 0-3.6 1.3-3.6 3.7v2.1H9.3v2.8h2.4V21h2.8z" fill="#fff"/>
                    </svg>
                </a>
                <a href="#" class="social-icon ig" aria-label="Instagram">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.6">
                        <rect x="3.5" y="3.5" width="17" height="17" rx="5"/>
                        <circle cx="12" cy="12" r="4"/>
                        <circle cx="17.2" cy="6.8" r="0.9" fill="#fff" stroke="none"/>
                    </svg>
                </a>
                <a href="#" class="social-icon yt" aria-label="YouTube">
                    <svg viewBox="0 0 24 24">
                        <path d="M9.5 8.3v7.4l6.5-3.7-6.5-3.7z" fill="#fff"/>
                    </svg>
                </a>
                <a href="#" class="social-icon tiktok" aria-label="TikTok">
                    <svg viewBox="0 0 24 24">
                        <path d="M14.3 4.1c.5 1.8 1.7 3 3.6 3.3v2.5c-1.3 0-2.5-.4-3.6-1.1v5.5a5 5 0 11-4.3-4.9v2.6a2.4 2.4 0 102 2.3V3.9h2.3z" fill="#25f4ee" transform="translate(-0.5,-0.5)"/>
                    </svg>
                </a>
                <a href="#" class="social-icon linkedin" aria-label="LinkedIn">
                    <svg width="28" height="28" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="false">
                        <rect width="48" height="48" rx="4" fill="#0077B5"/>
                        <text x="50%" y="58%" text-anchor="middle" font-family="Arial, Helvetica, sans-serif" font-size="22" font-weight="700" fill="#ffffff">in</text>
                    </svg>
                </a>
            </div>
        </div>

        <div class="footer-map">
            <p class="footer-heading">Find Us</p>
            <iframe
                src="https://www.google.com/maps?q=https://maps.app.goo.gl/gVMH8PVXJGhAGzyq5&output=embed"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                aria-label="aiZAP CREATIVES location"
            ></iframe>
            <div style="margin-top:8px;">
                <a href="https://maps.app.goo.gl/gVMH8PVXJGhAGzyq5" target="_blank" rel="noopener" class="footer-map-link">Open in Google Maps</a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">© 2026 aiZAP CREATIVES. All Rights Reserved.</div>
</footer>
@include('partials.video-modal')

@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
    @vite(['resources/js/video-modal.js', 'resources/css/video-modal.css'])
@else
    <script src="{{ asset('js/video-modal.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/video-modal.css') }}" />
@endif