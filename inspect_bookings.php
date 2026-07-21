<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Http\Request;
use App\Http\Controllers\BookingController;

$controller = new BookingController();
$date = '2026-07-21';
$timezone = 'Asia/Manila';
$request = Request::create('/api/booked-times', 'GET', ['date' => $date, 'timezone' => $timezone]);
$response = $controller->getBookedTimes($request);
$body = $response->getContent();
echo "RESPONSE: $body\n";
$bookings = App\Models\Booking::whereIn('status', ['pending', 'confirmed', 'rescheduled', 'completed'])->get();
echo "COUNT: " . $bookings->count() . "\n";
foreach ($bookings as $booking) {
    echo sprintf("%s | %s | %s | %s | %s\n", $booking->id, $booking->booking_date, $booking->booking_time, $booking->booking_timezone, $booking->status);
}
