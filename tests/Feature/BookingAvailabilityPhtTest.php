<?php

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('pht booked slot is excluded from availability when timezone Asia/Manila is requested', function () {
    Booking::factory()->create([
        'service' => 'Discovery Call',
        'status' => 'pending',
        'booking_date' => '2026-07-22',
        'booking_time' => '12:00',
        'booking_timezone' => 'Asia/Manila',
        'booking_local' => '2026-07-22 12:00',
        'booking_utc' => '2026-07-22 04:00:00',
    ]);

    $response = $this->getJson('/bookings/availability?booking_date=2026-07-22&booking_timezone=Asia/Manila&service=Discovery%20Call');

    $response->assertOk();
    expect($response->json('booked'))->toContain('12:00');
    expect($response->json('available'))->not->toContain('12:00');
});
