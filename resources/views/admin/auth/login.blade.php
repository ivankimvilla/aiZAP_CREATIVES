<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Login | aiZAP Admin</title>
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
        <style>
            * { box-sizing: border-box; }

            body {
                margin: 0;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Instrument Sans', system-ui, sans-serif;
                background: radial-gradient(circle at 50% 0%, #101a2e 0%, #090b10 60%);
                color: #f8fafc;
                padding: 1.5rem;
            }

            .auth-card {
                width: 100%;
                max-width: 440px;
                padding: 2.5rem 2.25rem;
                background: #0b1526;
                border: 1px solid #243148;
                border-radius: 24px;
                box-shadow: 0 28px 80px rgba(0, 0, 0, 0.35);
            }

            .auth-card h1 {
                margin: 0 0 2rem;
                font-size: 2rem;
                text-align: center;
                line-height: 1.1;
                letter-spacing: 0.04em;
            }

            .auth-card h1 span.brand-part { display: inline-block; font-weight: 800; }
            .auth-card h1 span.brand-part.ai { color: #ffd54f; }
            .auth-card h1 span.brand-part.zap { color: #ffffff; }
            .auth-card h1 span.title-sub {
                display: block;
                color: #94a3b8;
                font-weight: 600;
                font-size: 0.95rem;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                margin-top: 0.6rem;
            }

            .field { margin-top: 1.25rem; }

            .field label {
                display: block;
                margin-bottom: 0.5rem;
                font-size: 0.875rem;
                font-weight: 600;
                color: #cbd5e1;
            }

            .field input {
                width: 100%;
                padding: 0.95rem 1rem;
                border: 1px solid #334155;
                border-radius: 14px;
                background: #08111f;
                color: #f8fafc;
                font-size: 0.975rem;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
            }

            .field input:focus {
                outline: none;
                border-color: #fbbf24;
                box-shadow: 0 0 0 3px rgba(251, 191, 36, 0.18);
            }

            .field input::placeholder { color: #475569; }

            .password-wrap { position: relative; }

            .password-wrap input { padding-right: 3rem; }

            .toggle-password {
                position: absolute;
                right: 0.5rem;
                top: 50%;
                transform: translateY(-50%);
                width: 2.25rem;
                height: 2.25rem;
                display: flex;
                align-items: center;
                justify-content: center;
                background: transparent;
                border: none;
                border-radius: 8px;
                cursor: pointer;
                color: #64748b;
                transition: color 0.15s ease, background 0.15s ease;
            }

            .toggle-password:hover { color: #cbd5e1; background: rgba(255, 255, 255, 0.05); }
            .toggle-password svg { width: 20px; height: 20px; }
            .toggle-password .icon-off { display: none; }
            .toggle-password.is-visible .icon-on { display: none; }
            .toggle-password.is-visible .icon-off { display: block; }

            .field-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 0.75rem;
                margin-top: 1.25rem;
                font-size: 0.875rem;
                color: #94a3b8;
            }

            .remember-me { display: flex; align-items: center; gap: 0.45rem; cursor: pointer; }
            .remember-me input { accent-color: #fbbf24; width: 15px; height: 15px; }

            .auth-card a {
                color: #93c5fd;
                text-decoration: none;
                transition: color 0.15s ease;
            }
            .auth-card a:hover { color: #bfdbfe; }

            .auth-card button[type="submit"] {
                width: 100%;
                margin-top: 1.75rem;
                padding: 1rem 1rem;
                background: #fbbf24;
                color: #0f172a;
                font-weight: 700;
                font-size: 1rem;
                border: none;
                border-radius: 14px;
                cursor: pointer;
                transition: background 0.15s ease, transform 0.1s ease;
            }
            .auth-card button[type="submit"]:hover { background: #f5c94f; }
            .auth-card button[type="submit"]:active { transform: scale(0.99); }

            .alert, .status {
                margin-bottom: 1.25rem;
                padding: 0.9rem 1rem;
                border-radius: 14px;
                color: #f8fafc;
                font-size: 0.9rem;
                border: 1px solid transparent;
            }
            .alert { background: rgba(88, 28, 135, 0.35); border-color: #7e22ce; }
            .status { background: rgba(22, 78, 99, 0.35); border-color: #0e7490; }

            @media (max-width: 480px) {
                .auth-card { padding: 2rem 1.5rem; border-radius: 18px; }
            }
        </style>
    </head>
    <body>
        <div class="auth-card">
            <h1>
                <span class="brand-part ai">ai</span><span class="brand-part zap">ZAP</span>
                <span class="title-sub">Admin Login</span>
            </h1>

            @if($errors->any())
                <div class="alert">{{ $errors->first() }}</div>
            @endif

            @if(session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            <form method="post" action="{{ route('login.submit') }}">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', 'admin@aizap.com') }}" required autofocus />
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <div class="password-wrap">
                        <input id="password" name="password" type="password" required />
                        <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password" aria-pressed="false">
                            <!-- eye (show) -->
                            <svg class="icon-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            <!-- eye-off (hide) -->
                            <svg class="icon-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 7 11 7a13.16 13.16 0 0 1-1.67 2.68M6.61 6.61A13.53 13.53 0 0 0 1 11s4 7 11 7a9.26 9.26 0 0 0 5.39-1.61M14.12 14.12a3 3 0 1 1-4.24-4.24" />
                                <path d="M1 1l22 22" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="field-footer">
                    <label class="remember-me">
                        <input type="checkbox" name="remember" /> Remember me
                    </label>
                    <a href="{{ route('admin.password.request') }}">Forgot password?</a>
                </div>

                <button type="submit">Sign In</button>
            </form>
        </div>

        <script>
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            toggleBtn.addEventListener('click', () => {
                const isVisible = passwordInput.type === 'text';
                passwordInput.type = isVisible ? 'password' : 'text';
                toggleBtn.classList.toggle('is-visible', !isVisible);
                toggleBtn.setAttribute('aria-pressed', String(!isVisible));
                toggleBtn.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            });
        </script>
    </body>
</html>