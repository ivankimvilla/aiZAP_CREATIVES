<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Models\PackageRequest;
use App\Models\User;

uses(RefreshDatabase::class);

describe('admin package replies', function () {
    it('saves a reply and sends package reply email', function () {
        Mail::fake();

        $admin = User::factory()->create();
        $this->actingAs($admin);

        $packageRequest = PackageRequest::create([
            'name' => 'Acme Corp',
            'email' => 'sales@acme.test',
            'phone' => '+1 800 123 4567',
            'message' => 'Interested in the Growth package.',
            'package' => 'Growth',
            'status' => 'pending',
            'seen' => false,
        ]);

        $response = $this->post(route('admin.packages.reply', $packageRequest), [
            'reply_message' => 'Thanks for reaching out. We can help with your campaign.',
        ]);

        $response->assertRedirect(route('admin.packages.index'));
        $this->assertDatabaseHas('package_requests', [
            'id' => $packageRequest->id,
            'reply_message' => 'Thanks for reaching out. We can help with your campaign.',
            'status' => 'replied',
            'email_status' => 'sent',
        ]);
    });
});
