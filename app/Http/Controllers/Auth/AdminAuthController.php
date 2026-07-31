<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminPasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password as PasswordRule;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors(['email' => 'Invalid credentials provided.'])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    public function showRegisterForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:users,name'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                // Return validation errors for AJAX
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // If the failure is due to name/email uniqueness, show a single toast-like message.
            if ($validator->errors()->has('name') || $validator->errors()->has('email')) {
                return redirect()->route('admin.password.change')
                    ->withErrors(['registration' => 'The username or email is already taken.'])
                    ->withInput();
            }

            return redirect()->route('admin.password.change')
                ->withErrors($validator)
                ->withInput();
        }

        $password = $request->password;

        // If this is an AJAX/json request, return JSON responses so the UI can update in-place
        if ($request->expectsJson() || $request->ajax()) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            // If not logged in (unlikely for admin creation flow), log in and indicate redirect
            if (! Auth::check()) {
                Auth::login($user);
                return response()->json(['redirect' => route('admin.dashboard')]);
            }

            // Persist the reveal in session so the password toggle continues to work after page refresh.
            session()->flash('admin_password_reveal', ['id' => $user->id, 'password' => $password]);

            return response()->json([
                'status' => 'success',
                'message' => 'Admin account created successfully.',
                'admin' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                // provide the plaintext password only in the immediate AJAX response (not stored)
                'password' => $password,
            ], 201);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
        ]);

        if (! Auth::check()) {
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        session()->put('admin_password_reveal', ['id' => $user->id, 'password' => $password]);

        return redirect()->route('admin.password.change')
            ->with('status', 'Admin account created successfully.');
    }

    /**
     * AJAX endpoint to check uniqueness of admin name or email.
     */
    public function checkAdminUnique(Request $request)
    {
        $field = $request->input('field');
        $value = $request->input('value');

        if (! in_array($field, ['name', 'email'], true)) {
            return response()->json(['exists' => false]);
        }

        $exists = User::where($field, $value)->exists();

        if ($exists) {
            $message = $field === 'name' ? 'This username is already taken.' : 'This email address is already taken.';
            return response()->json(['exists' => true, 'message' => $message]);
        }

        return response()->json(['exists' => false]);
    }

    public function showForgotPasswordForm()
    {
        return view('admin.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);

        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'No admin account was found with that email address.']);
        }

        try {
            $this->ensureRealMailTransportIsConfigured();
        } catch (\RuntimeException $exception) {
            return back()->withErrors(['email' => $exception->getMessage()]);
        }

        $status = Password::broker('users')->sendResetLink([
            'email' => $request->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            $tokenRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();
            if (! $tokenRecord || empty($tokenRecord->token)) {
                return back()->withErrors(['email' => 'The reset token could not be created. Please check the database configuration.']);
            }

            return back()->with('status', 'Reset link sent to your email address.');
        }

        if ($status === Password::INVALID_USER) {
            return back()->withErrors(['email' => 'No admin account was found with that email address.']);
        }

        if ($status === Password::RESET_THROTTLED) {
            return back()->withErrors(['email' => 'Please wait before requesting another password reset link.']);
        }

        return back()->withErrors(['email' => 'Unable to send the password reset email. Please check mail settings or contact support.']);
    }

    public function sendPasswordResetToAdmin(Request $request, User $user)
    {
        $status = Password::broker('users')->sendResetLink([
            'email' => $user->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', "Password reset link sent to {$user->email}.");
        }

        return back()->withErrors(['email' => 'Unable to send the password reset email. Please check mail settings or contact support.']);
    }

    public function resetAdminPassword(Request $request, User $user)
    {
        $status = Password::broker('users')->sendResetLink([
            'email' => $user->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', "Password reset link sent to {$user->email}.");
        }

        return back()->withErrors(['email' => 'Unable to send the password reset email. Please check mail settings or contact support.']);
    }

    public function changeAdminPassword(Request $request, User $user)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $currentAdmin = Auth::user();
        if (! $currentAdmin || ! Hash::check($request->current_password, $currentAdmin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->put('admin_password_reveal', ['id' => $user->id, 'password' => $request->password]);

        return back()->with('status', "Password updated successfully for {$user->email}.");
    }

    public function destroyAdmin(Request $request, User $user)
    {
        $currentAdmin = Auth::user();
        if ($currentAdmin && $currentAdmin->id === $user->id) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $user->delete();
            return redirect()->route('login')->with('status', 'Your admin account has been deleted.');
        }

        $user->delete();
        return back()->with('status', 'Admin account deleted successfully.');
    }

    public function showResetForm(Request $request, $token = null)
    {
        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        if (! $record || ! $this->isValidResetToken($record, $token)) {
            return redirect()->route('admin.password.request')->withErrors(['email' => 'This password reset link is invalid or has expired.']);
        }

        return view('admin.auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $status = Password::broker('users')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->password = Hash::make($password);
                $user->save();

                Auth::login($user);
                session()->put('admin_password_reveal', ['id' => $user->id, 'password' => $password]);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('status', 'Your password has been reset. Please sign in with your new password.');
        }

        if ($status === Password::INVALID_USER) {
            return back()->withErrors(['email' => 'No admin account was found with that email address.']);
        }

        return back()->withErrors(['email' => 'This password reset link is invalid or has expired.']);
    }

    public function showChangePasswordForm()
    {
        return view('admin.auth.change-password', [
            'user' => Auth::user(),
            'admins' => User::orderBy('email')->get(),
        ]);
    }

    public function updateAccount(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back()->with('status', 'Account information updated successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'confirmed', PasswordRule::min(8)],
        ]);

        $user = $request->user();
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('status', 'Password updated successfully.')
            ->with('admin_password_reveal', ['id' => $user->id, 'password' => $request->password]);
    }

    private function sendPasswordResetLink(User $user): void
    {
        $this->ensureRealMailTransportIsConfigured();

        $status = Password::broker('users')->sendResetLink([
            'email' => $user->email,
        ]);

        if ($status !== Password::RESET_LINK_SENT) {
            throw new \RuntimeException('Unable to send the password reset email. Please check mail settings or contact support.');
        }
    }

    private function ensureRealMailTransportIsConfigured(): void
    {
        $mailer = config('mail.default');
        $smtpHost = config('mail.mailers.smtp.host');
        $smtpUser = config('mail.mailers.smtp.username');
        $smtpPassword = config('mail.mailers.smtp.password');

        if ($mailer === 'log' || $mailer === 'array') {
            throw new \RuntimeException('Mail delivery is disabled in the current app configuration. Set a live SMTP transport in Railway before using password reset.');
        }

        if ($mailer === 'smtp' && blank($smtpHost)) {
            throw new \RuntimeException('SMTP host is not configured. Set MAIL_HOST and the corresponding SMTP credentials in Railway before sending password reset emails.');
        }

        if ($mailer === 'smtp' && blank($smtpUser) && blank($smtpPassword)) {
            throw new \RuntimeException('SMTP credentials are missing. Add MAIL_USERNAME and MAIL_PASSWORD in Railway before sending password reset emails.');
        }
    }

    private function isValidResetToken(object $record, ?string $token): bool
    {
        if (! $record || ! $token) {
            return false;
        }

        if (! empty($record->used_at)) {
            return false;
        }

        if (empty($record->expires_at) || now()->greaterThan(now()->parse($record->expires_at))) {
            return false;
        }

        return Hash::check($token, $record->token);
    }
}
