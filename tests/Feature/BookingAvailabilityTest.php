<?php

use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('it excludes booked slots for the selected date and timezone', function () {
    Booking::factory()->create([
        'service' => 'Discovery Call',
        'status' => 'pending',
        'booking_date' => '2026-07-22',
        'booking_time' => '10:00',
        'booking_timezone' => 'Asia/Manila',
        'booking_local' => '2026-07-22 10:00',
        'booking_utc' => '2026-07-22 10:00:00',
    ]);

    $response = $this->getJson('/bookings/availability?booking_date=2026-07-22&booking_timezone=Asia/Manila&service=Discovery%20Call');

    $response->assertOk();
    $response->assertJsonPath('date', '2026-07-22');
    expect($response->json('booked'))->toContain('10:00');
    expect($response->json('available'))->not->toContain('10:00');
});

test('it makes a completed booking slot available again', function () {
    Booking::factory()->create([
        'service' => 'Discovery Call',
        'status' => 'completed',
        'booking_date' => '2026-07-22',
        'booking_time' => '11:00',
        'booking_timezone' => 'Asia/Manila',
        'booking_local' => '2026-07-22 11:00',
        'booking_utc' => '2026-07-22 11:00:00',
    ]);

    $response = $this->getJson('/bookings/availability?booking_date=2026-07-22&booking_timezone=Asia/Manila&service=Discovery%20Call');

    $response->assertOk();
    expect($response->json('booked'))->not->toContain('11:00');
    expect($response->json('available'))->toContain('11:00');
});
