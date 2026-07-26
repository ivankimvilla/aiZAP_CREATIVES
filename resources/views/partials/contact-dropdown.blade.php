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
                <div class="contact-info">
                    <p class="eyebrow">Get in Touch</p>
                    <ul class="contact-info__list">
                        <li class="contact-info__item">
                            <span class="contact-info__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 22s7-7.58 7-12.5A7 7 0 0 0 5 9.5C5 14.42 12 22 12 22Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                    <circle cx="12" cy="9.5" r="2.4" stroke="currentColor" stroke-width="1.6"/>
                                </svg>
                            </span>
                            <span>Davao City, Philippines</span>
                        </li>
                        <li class="contact-info__item">
                            <span class="contact-info__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="3" y="5" width="18" height="14" rx="2.2" stroke="currentColor" stroke-width="1.6"/>
                                    <path d="m4 6.5 8 6.2 8-6.2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <a href="mailto:aizapcreatives@gmail.com">aizapcreatives@gmail.com</a>
                        </li>
                        <li class="contact-info__item">
                            <span class="contact-info__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6.6 10.8c1.3 2.6 3.4 4.7 6 6l2-2c.3-.3.7-.4 1.1-.3 1.2.4 2.5.6 3.8.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1C10.6 21 3 13.4 3 4c0-.6.4-1 1-1h3.9c.6 0 1 .4 1 1 0 1.3.2 2.6.6 3.8.1.4 0 .8-.3 1.1l-2 2Z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </span>
                            <a href="tel:+639123456789">+63 912 345 6789</a>
                        </li>
                    </ul>
                </div>

                <div class="contact-form-col">
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
                    <h2 class="section-title">Send Us a Message</h2>
                    <p class="hero-sub">Tell us about your project and we’ll get back to you fast.</p>
                    <form class="contact-form" action="{{ url('/contact') }}" method="post">
                        @csrf
                        <input type="hidden" name="request_type" value="{{ $requestType ?: 'contact' }}">
                        <input type="hidden" name="package" value="{{ $package ?? '' }}">
                        <label>
                            Name
                            <input type="text" name="name" placeholder="Your name" value="{{ old('name') }}" required />
                        </label>
                        <label>
                            Email
                            <input type="email" name="email" placeholder="Your email" value="{{ old('email') }}" required />
                        </label>
                        <label>
                            Message
                            <textarea name="message" rows="5" placeholder="Tell us about your project." required>{{ old('message') }}</textarea>
                        </label>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>