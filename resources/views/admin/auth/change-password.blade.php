@extends('admin.layout')

@section('title', 'Account Settings')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/account-settings.css') }}" />
<main class="pw-page">
    <div class="pw-cards-row">
        <section class="password-card admin-list-card">
            <div class="admin-card-header">
                <div>
                    <h1 class="hero-title">Admin accounts</h1>
                    <p class="hero-sub">Manage administrator access</p>
                </div>
                <button type="button" class="btn-small hero-add-btn admin-open-modal">+ Add admin</button>
            </div>

            <div class="admin-list-toolbar">
                <span class="admin-list-pill">{{ count($admins ?? []) }} admin{{ count($admins ?? []) === 1 ? '' : 's' }}</span>
            </div>

            @php
                $statusMessage = session('status') ?? ($status ?? null);
                $reveal = session('admin_password_reveal') ?? ($adminPasswordReveal ?? null);
            @endphp

            @if($statusMessage)
                <div class="alert alert-success">
                    <span class="alert-icon">&#10003;</span>
                    {{ $statusMessage }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <span class="alert-icon">&#33;</span>
                    {{ $errors->first() }}
                </div>
            @endif

            @php
        $showCreateModal = old('name') || old('email') || old('password') || old('password_confirmation');
    @endphp

    @if(($admins ?? collect())->isEmpty())
                <p class="admin-list-empty">No admin accounts found yet.</p>
            @else
                <div class="admin-list">
                    <div class="admin-list-header">
                        <span>Email</span>
                        <span>Password</span>
                        <span></span>
                    </div>
                    @foreach($admins as $admin)
                        @php
                            $rowRevealPassword = $reveal && $reveal['id'] === $admin->id ? $reveal['password'] : null;
                        @endphp
                        <div class="admin-list-item">
                            <div class="admin-list-email-wrap">
                                <span id="admin-email-{{ $admin->id }}" class="admin-list-email">{{ $admin->email }}</span>
                                <button type="button" class="icon-btn copy-btn" data-copy-target="admin-email-{{ $admin->id }}" aria-label="Copy admin email">
                                    <svg class="icon-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                    <svg class="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                        <path d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="admin-list-password-wrap">
                                <span id="admin-password-{{ $admin->id }}" class="admin-list-password" data-masked="••••••••••" @if($rowRevealPassword) data-real="{{ $rowRevealPassword }}" @endif data-visible="false">••••••••••</span>
                                <button type="button" class="icon-btn toggle-row-password" data-target="admin-password-{{ $admin->id }}" aria-label="Show password" aria-pressed="false">
                                    <svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                    <svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                        <path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-7-11-7a20.9 20.9 0 0 1 5.06-6.06M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 7 11 7a20.9 20.9 0 0 1-4.22 5.5M14.12 14.12a3 3 0 1 1-4.24-4.24"></path>
                                        <line x1="1" y1="1" x2="23" y2="23"></line>
                                    </svg>
                                </button>
                                <button type="button" class="icon-btn copy-btn" data-copy-target="admin-password-{{ $admin->id }}" aria-label="Copy password">
                                    <svg class="icon-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                                    </svg>
                                    <svg class="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;">
                                        <path d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="admin-list-menu">
                                <button type="button" class="dots-btn" aria-label="Open admin actions" data-menu-toggle>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="5" r="1"></circle>
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="12" cy="19" r="1"></circle>
                                    </svg>
                                </button>
                                <div class="admin-dropdown" role="menu">
                                    <button type="button" class="dropdown-item" data-open-admin-change-password-url="{{ route('admin.admins.change_password', $admin) }}" data-admin-email="{{ $admin->email }}">Change Password</button>
                                    <form method="post" action="{{ route('admin.admins.destroy', $admin) }}" onsubmit="return confirm('Delete this admin account? This cannot be undone.');">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="dropdown-item">Delete Admin</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <div class="admin-modal-overlay{{ $showCreateModal ? ' open' : '' }}" id="adminModalOverlay">
        <div class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="adminModalTitle">
            <div class="admin-modal-head">
                <h3 id="adminModalTitle">Add Admin Account</h3>
                <button type="button" class="admin-modal-close" data-close-modal aria-label="Close add admin dialog">×</button>
            </div>
            <form method="post" action="{{ route('admin.register.submit') }}" class="password-form" autocomplete="off">
                @csrf
                <div class="form-group{{ $errors->has('name') ? ' invalid' : '' }}">
                    <label for="modal_admin_name">Full name <span class="required-star">*</span></label>
                    <input id="modal_admin_name" name="name" type="text" placeholder="Name" value="{{ old('name') }}" required autofocus autocomplete="off" />
                    @error('name')
                        <div class="live-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group{{ $errors->has('email') ? ' invalid' : '' }}">
                    <label for="modal_admin_email">Email <span class="required-star">*</span></label>
                    <input id="modal_admin_email" name="email" type="email" placeholder="Email" value="{{ old('email') }}" required autocomplete="off" />
                    @error('email')
                        <div class="live-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group{{ $errors->has('password') ? ' invalid' : '' }}">
                    <label for="modal_admin_password">Password <span class="required-star">*</span></label>
                    <div class="input-wrap">
                        <input id="modal_admin_password" name="password" type="password" placeholder="Create a password" required autocomplete="new-password" />
                        <button type="button" class="eye-toggle" data-target="modal_admin_password" aria-label="Show password">
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
                    @error('password')
                        <div class="live-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group{{ $errors->has('password_confirmation') ? ' invalid' : '' }}">
                    <label for="modal_admin_password_confirmation">Confirm password <span class="required-star">*</span></label>
                    <div class="input-wrap">
                        <input id="modal_admin_password_confirmation" name="password_confirmation" type="password" placeholder="Re-enter the password" required autocomplete="new-password" />
                        <button type="button" class="eye-toggle" data-target="modal_admin_password_confirmation" aria-label="Show password">
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
                    @error('password_confirmation')
                        <div class="live-error">{{ $message }}</div>
                    @enderror
                </div>
                <button type="submit" class="btn-primary">Create Account</button>
            </form>
        </div>
    </div>

    <div class="admin-modal-overlay" id="adminChangePasswordOverlay">
        <div class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="adminChangePasswordTitle">
            <div class="admin-modal-head">
                <div>
                    <h3 id="adminChangePasswordTitle">Change Admin Password</h3>
                    <p class="modal-subtitle" id="adminChangePasswordEmail"></p>
                </div>
                <button type="button" class="admin-modal-close" data-close-modal aria-label="Close change password dialog">×</button>
            </div>
            <form method="post" id="adminChangePasswordForm" class="password-form">
                @csrf
                <input type="text" name="username" autocomplete="username" hidden aria-hidden="true" tabindex="-1" />
                <div class="form-group">
                    <label for="admin_current_password">Current password</label>
                    <div class="input-wrap">
                        <input id="admin_current_password" name="current_password" type="password" placeholder="Enter your current password" required autocomplete="current-password" />
                        <button type="button" class="eye-toggle" data-target="admin_current_password" aria-label="Show password">
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
                    <label for="admin_change_password">New password</label>
                    <div class="input-wrap">
                        <input id="admin_change_password" name="password" type="password" placeholder="Enter a new password" required autocomplete="new-password" />
                        <button type="button" class="eye-toggle" data-target="admin_change_password" aria-label="Show password">
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
                    <label for="admin_change_password_confirmation">Confirm password</label>
                    <div class="input-wrap">
                        <input id="admin_change_password_confirmation" name="password_confirmation" type="password" placeholder="Re-enter the password" required autocomplete="new-password" />
                        <button type="button" class="eye-toggle" data-target="admin_change_password_confirmation" aria-label="Show password">
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
                <button type="submit" class="btn-primary">Change Password</button>
            </form>
        </div>
    </div>
</main>
<script src="{{ asset('js/admin/change-password.js') }}"></script>
@endsection