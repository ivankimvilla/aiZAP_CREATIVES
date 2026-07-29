<link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}">

<div class="contact-dropdown-component">
    <button type="button" class="contact-float-button contact-toggle" aria-label="Open contact form">Contact Us</button>

    @php
        $requestType = old('request_type') ?: request()->input('request_type');
        $package = old('package') ?: request()->input('package');
        $openContactDropdown = $errors->any() && in_array($requestType, ['contact', 'book_call']);
    @endphp
    <div class="contact-dropdown" id="contactDropdown" aria-hidden="true" data-initial-open="{{ $openContactDropdown ? 'true' : 'false' }}">
        <div class="contact-dropdown-inner">
            <button type="button" class="contact-dropdown-close" aria-label="Close contact form">×</button>
            @if(session('status'))
                <div class="contact-dropdown-status" id="contactDropdownStatus">{{ session('status') }}</div>
            @endif

            <div class="contact-dropdown-grid">
                <div class="contact-form-col">
                    <p class="eyebrow">Send Us a <span>Message</span></p>
                    <div class="eyebrow-rule"></div>

                    @if($errors->any())
                        <div class="contact-dropdown-errors">
                            <p>Please fix the following:</p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="contact-form" action="{{ url('/contact') }}" method="post">
                        @csrf
                        <input type="hidden" name="request_type" value="{{ $requestType ?: 'contact' }}">
                        <input type="hidden" name="package" value="{{ $package ?? '' }}">

                        <div class="contact-form-row">
                            <label>
                                <span class="field-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle cx="12" cy="8" r="3.4" stroke="currentColor" stroke-width="1.6"/>
                                        <path d="M4.5 19.2c1.4-3.4 4.3-5.2 7.5-5.2s6.1 1.8 7.5 5.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                                    </svg>
                                </span>
                                <input type="text" name="name" placeholder="Your Name" value="{{ old('name') }}" required />
                            </label>
                            <label>
                                <span class="field-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="3" y="5" width="18" height="14" rx="2.2" stroke="currentColor" stroke-width="1.6"/>
                                        <path d="m4 6.5 8 6.2 8-6.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                                <input type="email" name="email" placeholder="Your Email" value="{{ old('email') }}" required />
                            </label>
                        </div>

                        <div class="contact-form-row">
                            <label>
                                <span class="field-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect x="3" y="7.5" width="18" height="12" rx="2" stroke="currentColor" stroke-width="1.6"/>
                                        <path d="M8 7.5V6a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v1.5" stroke="currentColor" stroke-width="1.6"/>
                                    </svg>
                                </span>
                                <input type="text" name="company" placeholder="Company / Brand" value="{{ old('company') }}" />
                            </label>
                            <label>
                                <span class="field-icon" aria-hidden="true">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12.6 3.4 20.6 11.4a2 2 0 0 1 0 2.8l-6.4 6.4a2 2 0 0 1-2.8 0L3.4 12.6a2 2 0 0 1-.6-1.4V5a1.6 1.6 0 0 1 1.6-1.6h6.2c.5 0 1 .2 1.4.6Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                        <circle cx="8" cy="8" r="1.4" stroke="currentColor" stroke-width="1.6"/>
                                    </svg>
                                </span>
                                <input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}" />
                            </label>
                        </div>

                        <label class="contact-form-message">
                            <textarea name="message" rows="5" placeholder="Tell us about your project..." required>{{ old('message') }}</textarea>
                        </label>

                        <button type="submit" class="btn btn-primary">
                            Send Message
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="btn-arrow">
                                <path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <p class="contact-form-privacy">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <rect x="5" y="10.5" width="14" height="9.5" rx="1.8" stroke="currentColor" stroke-width="1.6"/>
                                <path d="M8 10.5V7.8a4 4 0 0 1 8 0v2.7" stroke="currentColor" stroke-width="1.6"/>
                            </svg>
                            We respect your privacy. Your information will never be shared.
                        </p>
                    </form>
                </div>

                <div class="contact-info-col">
                    <div class="contact-info-top">
                        <div class="contact-info">
                            <p class="eyebrow">Get in <span>Touch</span></p>
                            <div class="eyebrow-rule"></div>

                            <ul class="contact-info__list">
                                <li class="contact-info__item">
                                    <span class="contact-info__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="3" y="5" width="18" height="14" rx="2.2" stroke="currentColor" stroke-width="1.6"/>
                                            <path d="m4 6.5 8 6.2 8-6.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <span class="contact-info__text">
                                        <strong>Email Us</strong>
                                        <a href="mailto:aizapcreatives@gmail.com">aizapcreatives@gmail.com</a>
                                    </span>
                                </li>
                                <li class="contact-info__item">
                                    <span class="contact-info__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.6 10.8c1.3 2.6 3.4 4.7 6 6l2-2c.3-.3.7-.4 1.1-.3 1.2.4 2.5.6 3.8.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.9c.6 0 1 .4 1 1 0 1.3.2 2.6.6 3.8.1.4 0 .8-.3 1.1l-2 2Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <span class="contact-info__text">
                                        <strong>Call Us</strong>
                                        <a href="tel:+639123456789">+63 912 345 6789</a>
                                    </span>
                                </li>
                                <li class="contact-info__item">
                                    <span class="contact-info__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="m3 11 17-8-8 17-2.2-6.8L3 11Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <span class="contact-info__text">
                                        <strong>Location</strong>
                                        <span>Davao City, Philippines</span>
                                    </span>
                                </li>
                                <li class="contact-info__item">
                                    <span class="contact-info__icon" aria-hidden="true">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="8.4" stroke="currentColor" stroke-width="1.6"/>
                                            <path d="M12 7.6V12l3 2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </span>
                                    <span class="contact-info__text">
                                        <strong>Business Hours</strong>
                                        <span>Mon &ndash; Fri&nbsp; 9:00 AM &ndash; 6:00 PM (PHT)</span>
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="contact-map" aria-hidden="true">
                            <svg viewBox="0 0 260 220" xmlns="http://www.w3.org/2000/svg" class="contact-map__svg">
                                <defs>
                                    <pattern id="mapDots" width="8" height="8" patternUnits="userSpaceOnUse">
                                        <circle cx="1.2" cy="1.2" r="1.2" fill="rgba(212,175,55,0.28)"/>
                                    </pattern>
                                    <clipPath id="mapClip">
                                        <path d="M14 150c8-30 6-55 26-70 14-10 20-28 40-30 16-2 24 10 40 6 20-5 28-22 48-18 18 4 20 24 38 26 18 2 30-10 42-2 10 7 8 22-2 30-14 12-10 32-28 38-16 6-22-6-38-2-14 4-16 20-32 20-14 0-18-14-32-12-16 2-20 18-36 14-16-4-14-22-28-26-14-4-14 2-24-6-6-5-8-12-6-18Z"/>
                                    </clipPath>
                                </defs>
                                <rect x="6" y="18" width="248" height="184" fill="url(#mapDots)" clip-path="url(#mapClip)"/>
                                <g class="contact-map__lines" fill="none" stroke="rgba(212,175,55,0.55)" stroke-width="1">
                                    <path d="M60 90 Q110 40 150 70"/>
                                    <path d="M150 70 Q190 60 210 100"/>
                                    <path d="M60 90 Q90 140 140 150"/>
                                    <path d="M140 150 Q175 165 205 140"/>
                                    <path d="M150 70 Q160 110 140 150"/>
                                </g>
                                <g class="contact-map__points">
                                    <circle cx="60" cy="90" r="4"/>
                                    <circle cx="150" cy="70" r="4"/>
                                    <circle cx="210" cy="100" r="4"/>
                                    <circle cx="140" cy="150" r="4"/>
                                    <circle cx="205" cy="140" r="4"/>
                                    <circle cx="92" cy="120" r="2.6"/>
                                </g>
                            </svg>
                        </div>
                    </div>

                    <div class="contact-cta">
                        <div class="contact-cta__icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5 3c2.4 1 4.4 3 5.4 5.5.9 2.4.7 5-1 7.7l-2 2-2.3-2.3 1-2.6c.5-1.3.3-2.7-.6-3.6-.9-.9-2.3-1.1-3.6-.6l-2.6 1L5.5 7.8l2-2C10.2 4 12.8 3 13.5 3Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
                                <path d="M9 15l-1.5 1.5M6.5 12.5 5 14M14 3.2c-2.9.6-5.6 2-7.7 4.3M6 18c-1.4.3-2.4 1.3-2.7 2.7 1.4-.3 2.4-1.3 2.7-2.7Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="14.2" cy="9.8" r="1.6" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </div>
                        <p class="contact-cta__title">Let&rsquo;s create something <span>extraordinary together.</span></p>
                        <p class="contact-cta__text">Whether you have a clear plan or just an idea, we&rsquo;re ready to help you turn it into something powerful and unforgettable.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>