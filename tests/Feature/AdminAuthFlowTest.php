<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

it('sends a password reset link for an existing admin and stores a token', function () {
    Mail::fake();

    $user = User::factory()->create([
        'email' => 'admin@example.com',
    ]);

    $response = $this->post(route('admin.password.email'), [
        'email' => $user->email,
    ]);

    $response->assertSessionHas('status');
    Mail::assertQueued(\App\Mail\AdminPasswordResetMail::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });

    $tokenRecord = DB::table('password_reset_tokens')->where('email', $user->email)->first();
    expect($tokenRecord)->not->toBeNull();
    expect($tokenRecord->token)->not->toBeEmpty();
});

it('stops password reset requests after the daily limit is reached', function () {
    Mail::fake();

    $user = User::factory()->create([
        'email' => 'limited@example.com',
    ]);

    for ($i = 0; $i < 5; $i++) {
        $this->post(route('admin.password.email'), [
            'email' => $user->email,
        ])->assertSessionHasNoErrors();
    }

    $response = $this->post(route('admin.password.email'), [
        'email' => $user->email,
    ]);

    $response->assertSessionHasErrors(['email' => 'You have reached the daily password reset limit. Please try again tomorrow.']);
});

it('resets the password with a valid token and redirects to login', function () {
    $user = User::factory()->create([
        'email' => 'reset@example.com',
    ]);

    $token = 'valid-token';
    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make($token),
        'created_at' => now(),
        'expires_at' => now()->addMinute(),
        'request_count' => 1,
        'last_requested_at' => now(),
    ]);

    $response = $this->post(route('admin.password.update'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'NewPassword123',
        'password_confirmation' => 'NewPassword123',
    ]);

    $response->assertRedirect(route('login'));
    $response->assertSessionHas('status', 'Your password has been reset. Please sign in with your new password.');

    $user->refresh();
    expect(Hash::check('NewPassword123', $user->password))->toBeTrue();

    $tokenRecord = DB::table('password_reset_tokens')->where('email', $user->email)->first();
    expect($tokenRecord->used_at)->not->toBeNull();
});

it('renders an admin account list with add-admin controls and password actions', function () {
    $owner = User::factory()->create([
        'email' => 'owner@example.com',
    ]);
    $assistant = User::factory()->create([
        'email' => 'assistant@example.com',
    ]);

    $this->actingAs($owner);

    $response = $this->get(route('admin.password.change'));

    $response->assertOk();
    $response->assertSee('Add Admin');
    $response->assertSee('Forgot Password');
    $response->assertSee('Reset Password');
    $response->assertSee($assistant->email);
    $response->assertSee('••••••••••');
});
