@extends('admin.layout')

@section('title', 'Account Settings')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/change-password.css') }}" />
<link rel="stylesheet" href="{{ asset('css/admin/register.css') }}" />
<main class="pw-page">
    <section class="pw-hero">
        <h1 class="hero-title">Account Settings</h1>
        <p class="hero-sub">Manage your password and create additional admin accounts from one place.</p>
    </section>

    <div class="pw-cards-row">
        <section class="password-card">
            <div class="card-head">
                <span class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
                <div>
                    <h2 class="card-title">Change Password</h2>
                    <p class="card-sub">Update the password for your own account.</p>
                </div>
            </div>

            @if(session('status'))
                <div class="alert alert-success">
                    <span class="alert-icon">&#10003;</span>
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <span class="alert-icon">&#33;</span>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="post" action="{{ route('admin.password.change.update') }}" class="password-form">
                @csrf

                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <div class="input-wrap">
                        <input id="current_password" name="current_password" type="password" placeholder="Enter your current password" required autocomplete="current-password" />
                        <button type="button" class="eye-toggle" data-target="current_password" aria-label="Show password">
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
                    <label for="password">New Password</label>
                    <div class="input-wrap">
                        <input id="password" name="password" type="password" placeholder="Enter a new password" required autocomplete="new-password" />
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
                    <label for="password_confirmation">Confirm New Password</label>
                    <div class="input-wrap">
                        <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Re-enter your new password" required autocomplete="new-password" />
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

                <button type="submit" class="btn-primary">Update Password</button>
            </form>
        </section>

        <section class="password-card">
            <div class="card-head">
                <span class="card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M2 21v-2a4 4 0 0 1 4-4h6a4 4 0 0 1 4 4v2"></path>
                        <line x1="19" y1="8" x2="19" y2="14"></line>
                        <line x1="16" y1="11" x2="22" y2="11"></line>
                    </svg>
                </span>
                <div>
                    <h2 class="card-title">Create Admin Account</h2>
                    <p class="card-sub">Add another administrator to the dashboard.</p>
                </div>
            </div>

            <form method="post" action="{{ route('admin.register.submit') }}" class="password-form">
                @csrf

                <div class="form-group">
                    <label for="admin_name">Full name</label>
                    <input id="admin_name" name="name" type="text" placeholder="e.g. Jane Dela Cruz" value="{{ old('name') }}" required autofocus />
                </div>

                <div class="form-group">
                    <label for="admin_email">Email</label>
                    <input id="admin_email" name="email" type="email" placeholder="name@company.com" value="{{ old('email') }}" required />
                </div>

                <div class="form-group">
                    <label for="admin_password">Password</label>
                    <div class="input-wrap">
                        <input id="admin_password" name="password" type="password" placeholder="Create a password" required />
                        <button type="button" class="eye-toggle" data-target="admin_password" aria-label="Show password">
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
                    <label for="admin_password_confirmation">Confirm password</label>
                    <div class="input-wrap">
                        <input id="admin_password_confirmation" name="password_confirmation" type="password" placeholder="Re-enter the password" required />
                        <button type="button" class="eye-toggle" data-target="admin_password_confirmation" aria-label="Show password">
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
        </section>
    </div>
</main>
<script src="{{ asset('js/admin/change-password.js') }}"></script>
@endsection