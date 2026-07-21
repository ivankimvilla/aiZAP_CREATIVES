<?php

use App\Models\ContactMessage;
use App\Models\PackageRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('uses the latest activity timestamp for package requests', function () {
    $packageRequest = PackageRequest::create([
        'name' => 'Acme Corp',
        'email' => 'sales@acme.test',
        'phone' => '+1 800 123 4567',
        'message' => 'Interested in the Growth package.',
        'package' => 'Growth',
        'status' => 'pending',
        'seen' => false,
    ]);

    $originalCreatedAt = $packageRequest->created_at;

    $packageRequest->update([
        'reply_message' => 'Thanks for reaching out.',
        'status' => 'replied',
    ]);

    $packageRequest->refresh();

    expect($packageRequest->display_timestamp)
        ->not->toBeNull()
        ->and($packageRequest->display_timestamp->greaterThanOrEqualTo($originalCreatedAt))->toBeTrue()
        ->and($packageRequest->display_timestamp->equalTo($packageRequest->updated_at))->toBeTrue();
});

it('uses the latest activity timestamp for contact messages', function () {
    $message = ContactMessage::create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
        'phone' => '+1 555 555 5555',
        'message' => 'I would like to learn more about your services.',
        'request_type' => 'general',
        'package' => null,
        'status' => 'pending',
        'seen' => false,
    ]);

    $originalCreatedAt = $message->created_at;

    $message->update([
        'reply_message' => 'We got your message.',
        'status' => 'replied',
    ]);

    $message->refresh();

    expect($message->display_timestamp)
        ->not->toBeNull()
        ->and($message->display_timestamp->greaterThanOrEqualTo($originalCreatedAt))->toBeTrue()
        ->and($message->display_timestamp->equalTo($message->updated_at))->toBeTrue();
});
