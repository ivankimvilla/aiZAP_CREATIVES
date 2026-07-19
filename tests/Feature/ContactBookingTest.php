<?php

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;

describe('contact flow', function () {
    uses(RefreshDatabase::class);

    it('returns a scheduling confirmation for book-a-call requests', function () {
        $response = $this->post('/contact', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'I would like to book a call.',
            'request_type' => 'book_call',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'Thanks! We will contact you soon to schedule your call.');
    });

    it('stores contact submissions and allows an admin to reply', function () {
        Mail::fake();

        $admin = User::factory()->create();
        $this->actingAs($admin);

        $message = ContactMessage::create([
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'message' => 'I want to discuss a project.',
            'request_type' => 'contact',
        ]);

        $response = $this->post(route('admin.contacts.reply', $message), [
            'reply_subject' => 'Re: Your message to aiZAP Creatives',
            'reply_message' => 'We will follow up shortly.',
            'recipient_email' => 'john@example.com',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('contact_messages', [
            'id' => $message->id,
            'reply_subject' => 'Re: Your message to aiZAP Creatives',
            'reply_message' => 'We will follow up shortly.',
            'status' => 'replied',
            'email_status' => 'sent',
        ]);
    });
});
