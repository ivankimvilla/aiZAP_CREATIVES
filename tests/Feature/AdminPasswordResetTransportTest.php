<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;

it('returns an error instead of a false success when the app mail transport is not a real SMTP mailer', function () {
    Mail::fake();

    config(['mail.default' => 'log']);

    $user = User::factory()->create([
        'email' => 'admin1@example.com',
        'name' => 'Admin One',
    ]);

    $response = $this->from('/admin/forgot-password')->post('/admin/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertRedirect('/admin/forgot-password');
    $response->assertSessionHasErrors('email');
    $response->assertSessionHas('errors', function ($errors) {
        return str_contains($errors->get('email')[0], 'Mail delivery is disabled');
    });
});
