<?php

use App\Models\ContactMessage;
use App\Models\Reply;

it('persists replies for contact messages', function () {
    $message = ContactMessage::create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'phone' => '123456789',
        'message' => 'Hello from the test suite.',
        'request_type' => 'contact',
        'status' => 'pending',
    ]);

    $reply = Reply::create([
        'replyable_type' => ContactMessage::class,
        'replyable_id' => $message->id,
        'author' => 'Admin',
        'message' => 'Thanks for reaching out.',
    ]);

    expect($reply->wasRecentlyCreated)->toBeTrue();

    $this->assertDatabaseHas('replies', [
        'replyable_type' => ContactMessage::class,
        'replyable_id' => $message->id,
        'author' => 'Admin',
        'message' => 'Thanks for reaching out.',
    ]);
});
