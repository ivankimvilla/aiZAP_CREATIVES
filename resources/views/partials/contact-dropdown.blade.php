<link rel="stylesheet" href="{{ asset('css/contact-drop-down.css') }}">

<div class="contact-dropdown-component">
    <button type="button" class="contact-float-button contact-toggle" aria-label="Open contact form">Contact</button>

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

