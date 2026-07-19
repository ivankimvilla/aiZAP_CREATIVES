<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Booking;

describe('booking flow', function () {
    uses(RefreshDatabase::class);

    it('stores a booking from the public form', function () {
        $response = $this->post('/bookings', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'phone' => '+1 555 0100',
            'company' => 'Studio',
            'message' => 'Need a discovery call.',
            'booking_utc' => '2026-07-20T14:00:00.000Z',
            'booking_local' => '2026-07-20 14:00',
            'booking_timezone' => 'America/New_York',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Thanks! Your booking request has been received.');
        $this->assertDatabaseHas('bookings', [
            'email' => 'jane@example.com',
            'status' => 'pending',
            'booking_timezone' => 'America/New_York',
        ]);
    });

    it('stores a friendly timezone label as a canonical timezone for admin tracking', function () {
        $response = $this->post('/bookings', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'booking_utc' => '2026-07-20T14:00:00.000Z',
            'booking_local' => '2026-07-20 14:00',
            'booking_timezone' => 'Pacific Time (PT)',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'email' => 'jane@example.com',
            'booking_timezone' => 'America/Los_Angeles',
        ]);
    });

    it('rejects a duplicate booking for the same date and time', function () {
        Booking::factory()->create([
            'booking_date' => '2026-07-20',
            'booking_time' => '14:00',
            'service' => 'Discovery Call',
            'status' => 'confirmed',
        ]);

        $response = $this->post('/bookings', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'booking_date' => '2026-07-20',
            'booking_time' => '14:00',
            'service' => 'Discovery Call',
            'booking_timezone' => 'Asia/Manila',
        ]);

        $response->assertSessionHasErrors(['booking_time']);
    });

    it('rejects a booking that conflicts after converting to Philippine time', function () {
        Booking::factory()->create([
            'booking_date' => '2026-07-20',
            'booking_time' => '14:00',
            'booking_timezone' => 'UTC',
            'booking_utc' => '2026-07-20T14:00:00Z',
            'booking_local' => '2026-07-20 14:00',
            'service' => 'Discovery Call',
            'status' => 'confirmed',
        ]);

        $response = $this->post('/bookings', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'booking_date' => '2026-07-20',
            'booking_time' => '02:00',
            'service' => 'Discovery Call',
            'booking_timezone' => 'America/New_York',
        ]);

        $response->assertSessionHasErrors(['booking_time']);
    });

    it('rejects Sunday bookings before creating a booking', function () {
        $response = $this->post('/bookings', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'booking_date' => '2026-07-19',
            'booking_time' => '10:00',
            'service' => 'Discovery Call',
            'booking_timezone' => 'Asia/Manila',
        ]);

        $response->assertSessionHasErrors(['booking_time']);
        $this->assertDatabaseMissing('bookings', [
            'email' => 'jane@example.com',
        ]);
    });

    it('returns availability for a selected date and timezone', function () {
        Booking::factory()->create([
            'booking_date' => '2026-07-20',
            'booking_time' => '14:00',
            'booking_timezone' => 'Asia/Manila',
            'booking_utc' => '2026-07-20T06:00:00Z',
            'booking_local' => '2026-07-20 14:00',
            'service' => 'Discovery Call',
            'status' => 'confirmed',
        ]);

        $response = $this->get('/bookings/availability?booking_date=2026-07-20&booking_timezone=Asia/Manila&service=Discovery%20Call');

        $response->assertOk();
        $response->assertJsonFragment(['time' => '14:00']);
    });

    it('allows an admin to update booking status and view the dashboard', function () {
        $booking = Booking::factory()->create([
            'status' => 'pending',
        ]);

        $response = $this->post(route('admin.bookings.status.update', $booking), [
            'status' => 'confirmed',
            'admin_notes' => 'Confirmed for next week.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'confirmed',
            'admin_notes' => 'Confirmed for next week.',
        ]);

        $view = $this->get('/admin/bookings');
        $view->assertOk();
        $view->assertSee('confirmed');
    });
});
