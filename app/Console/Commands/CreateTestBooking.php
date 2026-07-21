<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;

class CreateTestBooking extends Command
{
    protected $signature = 'test:booking';
    protected $description = 'Create sample bookings for testing timezone persistence and PHT conversion';

    public function handle()
    {
        $this->info('Creating sample bookings...');

        $samples = [
            [
                'name' => 'Test PT',
                'email' => 'pt@example.test',
                'booking_date' => date('Y-m-d', strtotime('+1 day')),
                'booking_time' => '12:00',
                'booking_timezone' => 'America/Los_Angeles',
            ],
            [
                'name' => 'Test MT',
                'email' => 'mt@example.test',
                'booking_date' => date('Y-m-d', strtotime('+2 days')),
                'booking_time' => '14:00',
                'booking_timezone' => 'America/Denver',
            ],
            [
                'name' => 'Test SGT',
                'email' => 'sgt@example.test',
                'booking_date' => date('Y-m-d', strtotime('+3 days')),
                'booking_time' => '12:00',
                'booking_timezone' => 'Asia/Singapore',
            ],
        ];

        foreach ($samples as $data) {
            // Normalize timezone and compute UTC and PHT values similar to controller logic
            $timezone = $data['booking_timezone'] ?? 'Asia/Manila';
            try {
                $local = new \DateTimeImmutable("{$data['booking_date']} {$data['booking_time']}", new \DateTimeZone($timezone));
                $booking_utc = $local->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
                $booking_local = $local->format('Y-m-d H:i');

                $philippine = (new \App\Http\Controllers\BookingController())->convertToPhilippineDateTime($data['booking_date'], $data['booking_time'], $timezone);

                $booking = Booking::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'booking_utc' => $booking_utc,
                    'booking_local' => $booking_local,
                    'booking_timezone' => $timezone,
                    'booking_date' => $philippine['date'],
                    'booking_time' => $philippine['time'],
                    'service' => 'Discovery Call',
                ]);

                $this->line("Created booking #{$booking->id}:");
                $this->line("  booking_local: {$booking->booking_local}");
                $this->line("  booking_utc: {$booking->booking_utc}");
                $this->line("  booking_date (PHT): {$booking->booking_date}");
                $this->line("  booking_time (PHT): {$booking->booking_time}");
                $this->line('');
            } catch (\Exception $e) {
                $this->error('Failed to create sample booking: ' . $e->getMessage());
            }
        }

        $this->info('Done.');
        return 0;
    }
}
