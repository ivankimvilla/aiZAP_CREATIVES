<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Forgot Password | aiZAP Admin</title>
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
                background: #090b10;
                color: #f8fafc;
                padding: 1.5rem;
            }
            .auth-card {
                width: 100%;
                max-width: 440px;
                padding: 2.25rem 2rem;
                background: #0b1526;
                border: 1px solid #243148;
                border-radius: 24px;
                box-shadow: 0 28px 80px rgba(0,0,0,0.32);
            }
            .auth-card h1 {
                margin: 0 0 1.75rem;
                font-size: 2rem;
                text-align: center;
                line-height: 1.15;
                letter-spacing: 0.04em;
            }
            .auth-card h1 span:first-child { display: block; color: #ffffff; font-weight: 800; }
            .auth-card h1 span:last-child { display: block; color: #ffd54f; font-weight: 700; }

            .auth-card label {
                display: block;
                margin-top: 1rem;
                font-size: 0.9rem;
                font-weight: 500;
                color: #cbd5e1;
            }
            .auth-card input {
                width: 100%;
                margin-top: 0.5rem;
                padding: 0.95rem 1rem;
                border: 1px solid #334155;
                border-radius: 14px;
                background: #08111f;
                color: #f8fafc;
                font-size: 0.95rem;
                transition: border-color 0.15s ease, box-shadow 0.15s ease;
            }
            .auth-card input::placeholder { color: #64748b; }
            .auth-card input:focus {
                outline: none;
                border-color: #ffd54f;
                box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.18);
            }

            .auth-card button {
                width: 100%;
                margin-top: 1.75rem;
                padding: 0.95rem 1rem;
                background: #fbbf24;
                color: #0f172a;
                font-weight: 700;
                font-size: 0.95rem;
                border: none;
                border-radius: 14px;
                cursor: pointer;
                transition: background 0.15s ease, transform 0.1s ease;
            }
            .auth-card button:hover { background: #ffd54f; }
            .auth-card button:active { transform: scale(0.98); }
            .auth-card button:focus-visible {
                outline: none;
                box-shadow: 0 0 0 3px rgba(255, 213, 79, 0.35);
            }

            .back-link {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.4rem;
                margin-top: 1.25rem;
                padding: 0.6rem 1rem;
                color: #93c5fd;
                font-size: 0.9rem;
                font-weight: 500;
                text-decoration: none;
                border-radius: 10px;
                transition: background 0.15s ease, color 0.15s ease, gap 0.15s ease;
            }
            .back-link:hover {
                background: rgba(147, 197, 253, 0.08);
                color: #bfdbfe;
                gap: 0.55rem;
            }
            .back-link svg { flex-shrink: 0; transition: transform 0.15s ease; }
            .back-link:hover svg { transform: translateX(-2px); }

            .auth-card .alert, .auth-card .status {
                margin-bottom: 1rem;
                padding: 0.95rem 1rem;
                border-radius: 14px;
                color: #f8fafc;
                font-size: 0.9rem;
            }
            .auth-card .alert { background: #581c87; }
            .auth-card .status { background: #164e63; }
        </style>
    </head>
    <body>
        <div class="auth-card">
            <h1><span>aiZAP</span><span>Forgot Password</span></h1>

            @if(session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert">{{ $errors->first() }}</div>
            @endif

            <form method="post" action="{{ route('admin.password.email') }}">
                @csrf

                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="admin@example.com" />

                <button type="submit">Send reset link</button>
            </form>

            <a href="{{ route('login') }}" class="back-link">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Back to login
            </a>
        </div>
    </body>
</html>