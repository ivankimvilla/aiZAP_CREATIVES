<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Reset Password | aiZAP Admin</title>
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800" rel="stylesheet" />
        <style>
            body { margin:0; min-height:100vh; display:flex; align-items:center; justify-content:center; font-family:system-ui, sans-serif; background:#090b10; color:#f8fafc; }
            .auth-card { width:100%; max-width:440px; padding:2rem; background:#0b1526; border:1px solid #243148; border-radius:24px; box-shadow:0 28px 80px rgba(0,0,0,0.32); }
            .auth-card h1 { margin:0 0 1.5rem; font-size:2rem; text-align:center; line-height:1.1; letter-spacing:0.04em; }
            .auth-card h1 span:first-child { display:block; color:#ffffff; font-weight:800; }
            .auth-card h1 span:last-child { display:block; color:#ffd54f; font-weight:700; }
            .auth-card label { display:block; margin-top:1rem; font-size:0.95rem; color:#cbd5e1; }
            .auth-card input { width:100%; margin-top:0.5rem; padding:0.95rem 1rem; border:1px solid #334155; border-radius:14px; background:#08111f; color:#f8fafc; }
            .auth-card button { width:100%; margin-top:1.5rem; padding:0.95rem 1rem; background:#fbbf24; color:#0f172a; font-weight:700; border:none; border-radius:14px; cursor:pointer; }
            .auth-card a { display:inline-block; margin-top:1rem; color:#93c5fd; text-decoration:none; }
            .auth-card .alert, .auth-card .status { margin-bottom:1rem; padding:0.95rem 1rem; border-radius:14px; color:#f8fafc; }
            .auth-card .alert { background:#581c87; }
            .auth-card .status { background:#164e63; }
        </style>
    </head>
    <body>
        <div class="auth-card">
            <h1><span>aiZAP</span><span>Reset Password</span></h1>

            @if(session('status'))
                <div class="status">{{ session('status') }}</div>
            @endif

            @if($errors->any())
                <div class="alert">{{ $errors->first() }}</div>
            @endif

            <form method="post" action="{{ route('admin.password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}" />

                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required autofocus />

                <label for="password">Password</label>
                <input id="password" name="password" type="password" required autocomplete="new-password" />

                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password" />

                <button type="submit">Reset Password</button>
            </form>
        </div>
    </body>
</html>
