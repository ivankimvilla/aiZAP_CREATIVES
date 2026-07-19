<?php

namespace Database\Factories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'company' => $this->faker->company(),
            'message' => $this->faker->sentence(),
            'booking_utc' => now()->addDays(2)->setTime(14, 0),
            'booking_local' => '2026-07-20 14:00',
            'booking_timezone' => 'UTC',
            'status' => 'pending',
            'admin_notes' => null,
            'meeting_duration' => '30 minutes',
        ];
    }
}
