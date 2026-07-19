@extends('admin.layout')

@section('title', 'Create Admin Account')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/register.css') }}" />
    <main class="pw-page">
        <section class="pw-hero">
            <p class="eyebrow">Admin</p>
            <h1 class="hero-title">Create Admin Account</h1>
            <p class="hero-sub">Add a new admin account directly from the dashboard.</p>
        </section>

        <section class="password-card">
            @if($errors->any())
                <div class="alert alert-error">
                    <span class="alert-icon">&#33;</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="post" action="{{ route('admin.register.submit') }}" class="password-form">
                @csrf

                <div class="form-group">
                    <label for="name">Full name</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus />
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required />
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <input id="password" name="password" type="password" required />
                        <button type="button" class="eye-toggle" data-target="password" aria-label="Show password">
                            <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-7-11-7a20.9 20.9 0 0 1 5.06-6.06M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 7 11 7a20.9 20.9 0 0 1-4.22 5.5M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm password</label>
                    <div class="input-wrap">
                        <input id="password_confirmation" name="password_confirmation" type="password" required />
                        <button type="button" class="eye-toggle" data-target="password_confirmation" aria-label="Show password">
                            <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-7-11-7a20.9 20.9 0 0 1 5.06-6.06M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 7 11 7a20.9 20.9 0 0 1-4.22 5.5M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Create Account</button>
            </form>

            <div class="note">
                Already have an account? <a href="{{ route('login') }}">Sign in</a>
            </div>
        </section>
    </main>


<script src="{{ asset('js/admin/change-password.js') }}"></script>
@endsection