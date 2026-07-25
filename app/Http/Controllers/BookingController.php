<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    private function normalizeTimeZone(?string $value): string
    {
        $normalized = trim((string) ($value ?? ''));

        if ($normalized === '') {
            return 'Asia/Manila';
        }

        $friendlyToCanonical = [
            'Pacific Time (PT)' => 'America/Los_Angeles',
            'Mountain Time (MT)' => 'America/Denver',
            'Central Time (CT)' => 'America/Chicago',
            'Eastern Time (ET)' => 'America/New_York',
            'Alaska Time (AKT)' => 'America/Anchorage',
            'Hawaii Time (HST)' => 'Pacific/Honolulu',
            'Atlantic Time (AT)' => 'America/Halifax',
            'Newfoundland Time (NT)' => 'America/St_Johns',
            'Greenwich Mean Time (GMT)' => 'Europe/London',
            'Central European Time (CET)' => 'Europe/Paris',
            'Eastern European Time (EET)' => 'Europe/Athens',
            'Moscow Time (MSK)' => 'Europe/Moscow',
            'Gulf Standard Time (GST)' => 'Asia/Dubai',
            'Pakistan Standard Time (PKT)' => 'Asia/Karachi',
            'India Standard Time (IST)' => 'Asia/Kolkata',
            'Bangladesh Standard Time (BST)' => 'Asia/Dhaka',
            'China Standard Time (CST)' => 'Asia/Shanghai',
            'Philippine Standard Time (PHT)' => 'Asia/Manila',
            'Singapore Standard Time (SGT)' => 'Asia/Singapore',
            'Japan Standard Time (JST)' => 'Asia/Tokyo',
            'Korea Standard Time (KST)' => 'Asia/Seoul',
            'Australian Central Time (ACST)' => 'Australia/Adelaide',
            'Australian Eastern Time (AET)' => 'Australia/Sydney',
            'New Zealand Time (NZST)' => 'Pacific/Auckland',
            'Irish Standard Time (IST)' => 'Europe/Dublin',
            'Thailand Standard Time (ICT)' => 'Asia/Bangkok',
            'Vietnam Standard Time (ICT)' => 'Asia/Ho_Chi_Minh',
            'Malaysia Standard Time (MYT)' => 'Asia/Kuala_Lumpur',
            'Hong Kong Standard Time (HKT)' => 'Asia/Hong_Kong',
            'Taiwan Standard Time (CST)' => 'Asia/Taipei',
            'Mexico Standard Time (CST)' => 'America/Mexico_City',
            'Argentina Standard Time (ART)' => 'America/Argentina/Buenos_Aires',
            'Peru Standard Time (PET)' => 'America/Lima',
            'Colombia Standard Time (COT)' => 'America/Bogota',
            'Venezuela Time (VET)' => 'America/Caracas',
            'South Africa Standard Time (SAST)' => 'Africa/Johannesburg',
            'Egypt Standard Time (EET)' => 'Africa/Cairo',
            'Nigeria Standard Time (WAT)' => 'Africa/Lagos',
            'Kenya Standard Time (EAT)' => 'Africa/Nairobi',
            'Brazil Standard Time (BRT)' => 'America/Sao_Paulo',
            'Brazil Time (AMT)' => 'America/Manaus',
            'UTC' => 'UTC',
        ];

        if (isset($friendlyToCanonical[$normalized])) {
            return $friendlyToCanonical[$normalized];
        }

        try {
            new \DateTimeZone($normalized);

            return $normalized;
        } catch (\Exception $exception) {
            return 'Asia/Manila';
        }
    }

    private function formatTimeZoneLabel(?string $value): string
    {
        $normalized = trim((string) ($value ?? ''));

        if ($normalized === '') {
            return 'Philippine Standard Time (PHT)';
        }

        $canonicalToFriendly = [
            'America/Los_Angeles' => 'Pacific Time (PT)',
            'America/Denver' => 'Mountain Time (MT)',
            'America/Chicago' => 'Central Time (CT)',
            'America/New_York' => 'Eastern Time (ET)',
            'America/Anchorage' => 'Alaska Time (AKT)',
            'Pacific/Honolulu' => 'Hawaii Time (HST)',
            'America/Halifax' => 'Atlantic Time (AT)',
            'America/St_Johns' => 'Newfoundland Time (NT)',
            'Europe/London' => 'Greenwich Mean Time (GMT)',
            'Europe/Dublin' => 'Irish Standard Time (IST)',
            'Europe/Paris' => 'Central European Time (CET)',
            'Europe/Athens' => 'Eastern European Time (EET)',
            'Europe/Moscow' => 'Moscow Time (MSK)',
            'Asia/Dubai' => 'Gulf Standard Time (GST)',
            'Asia/Karachi' => 'Pakistan Standard Time (PKT)',
            'Asia/Kolkata' => 'India Standard Time (IST)',
            'Asia/Dhaka' => 'Bangladesh Standard Time (BST)',
            'Asia/Bangkok' => 'Thailand Standard Time (ICT)',
            'Asia/Ho_Chi_Minh' => 'Vietnam Standard Time (ICT)',
            'Asia/Kuala_Lumpur' => 'Malaysia Standard Time (MYT)',
            'Asia/Singapore' => 'Singapore Standard Time (SGT)',
            'Asia/Hong_Kong' => 'Hong Kong Standard Time (HKT)',
            'Asia/Shanghai' => 'China Standard Time (CST)',
            'Asia/Taipei' => 'Taiwan Standard Time (CST)',
            'Asia/Manila' => 'Philippine Standard Time (PHT)',
            'Asia/Tokyo' => 'Japan Standard Time (JST)',
            'Asia/Seoul' => 'Korea Standard Time (KST)',
            'Australia/Adelaide' => 'Australian Central Time (ACST)',
            'Australia/Sydney' => 'Australian Eastern Time (AET)',
            'Pacific/Auckland' => 'New Zealand Time (NZST)',
            'America/Mexico_City' => 'Mexico Standard Time (CST)',
            'America/Argentina/Buenos_Aires' => 'Argentina Standard Time (ART)',
            'America/Lima' => 'Peru Standard Time (PET)',
            'America/Bogota' => 'Colombia Standard Time (COT)',
            'America/Caracas' => 'Venezuela Time (VET)',
            'Africa/Johannesburg' => 'South Africa Standard Time (SAST)',
            'Africa/Cairo' => 'Egypt Standard Time (EET)',
            'Africa/Lagos' => 'Nigeria Standard Time (WAT)',
            'Africa/Nairobi' => 'Kenya Standard Time (EAT)',
            'America/Sao_Paulo' => 'Brazil Standard Time (BRT)',
            'America/Manaus' => 'Brazil Time (AMT)',
            'UTC' => 'UTC',
        ];

        if (isset($canonicalToFriendly[$normalized])) {
            return $canonicalToFriendly[$normalized];
        }

        if (isset($friendlyToCanonical[$normalized])) {
            return $normalized;
        }

        return $normalized;
    }

    private function convertToPhilippineDateTime(string $bookingDate, string $bookingTime, string $bookingTimezone): array
    {
        $timezone = $this->normalizeTimeZone($bookingTimezone);
        $dateTime = new \DateTimeImmutable("{$bookingDate} {$bookingTime}", new \DateTimeZone($timezone));
        $philippineTime = $dateTime->setTimezone(new \DateTimeZone('Asia/Manila'));

        return [
            'date' => $philippineTime->format('Y-m-d'),
            'time' => $philippineTime->format('H:i'),
        ];
    }

    private function convertPhilippineDateTimeToTimezone(string $bookingDate, string $bookingTime, string $bookingTimezone): array
    {
        $timezone = $this->normalizeTimeZone($bookingTimezone);
        $dateTime = new \DateTimeImmutable("{$bookingDate} {$bookingTime}", new \DateTimeZone('Asia/Manila'));
        $targetTime = $dateTime->setTimezone(new \DateTimeZone($timezone));

        return [
            'date' => $targetTime->format('Y-m-d'),
            'time' => $targetTime->format('H:i'),
        ];
    }

    private function resolvePhilippineDateTime(Booking $booking): array
    {
        if (!empty($booking->booking_date) && !empty($booking->booking_time)) {
            return [
                'date' => $booking->booking_date,
                'time' => $booking->booking_time,
            ];
        }

        if (!empty($booking->booking_utc)) {
            $utc = new \DateTimeImmutable($booking->booking_utc, new \DateTimeZone('UTC'));
            $philippineTime = $utc->setTimezone(new \DateTimeZone('Asia/Manila'));

            return [
                'date' => $philippineTime->format('Y-m-d'),
                'time' => $philippineTime->format('H:i'),
            ];
        }

        if (!empty($booking->booking_local) && !empty($booking->booking_timezone)) {
            $timezone = $this->normalizeTimeZone($booking->booking_timezone);
            $dateTime = new \DateTimeImmutable($booking->booking_local, new \DateTimeZone($timezone));
            $philippineTime = $dateTime->setTimezone(new \DateTimeZone('Asia/Manila'));

            return [
                'date' => $philippineTime->format('Y-m-d'),
                'time' => $philippineTime->format('H:i'),
            ];
        }

        return [
            'date' => null,
            'time' => null,
        ];
    }

    private function getActiveBookingStatuses(): array
    {
        return ['pending', 'confirmed', 'rescheduled'];
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'company' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string', 'max:2000'],
            'booking_utc' => ['nullable', 'date'],
            'booking_local' => ['nullable', 'string', 'max:255'],
            'booking_timezone' => ['required', 'string', 'max:255'],
            'booking_date' => ['nullable', 'date'],
            'booking_time' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        if (empty($data['service'])) {
            $data['service'] = 'Discovery Call';
        }

        $normalizedTimeZone = $this->normalizeTimeZone($data['booking_timezone'] ?? null);
        $data['booking_timezone'] = $normalizedTimeZone;

        $bookingDate = $data['booking_date'] ?? null;
        $bookingTime = $data['booking_time'] ?? null;
        $bookingUtc = $data['booking_utc'] ?? null;
        $bookingLocal = $data['booking_local'] ?? null;
        $timeZone = $normalizedTimeZone;

        try {
            if ((!$bookingUtc || !$bookingLocal) && $bookingDate && $bookingTime) {
                $local = new \DateTimeImmutable("{$bookingDate} {$bookingTime}", new \DateTimeZone($timeZone));
                $bookingUtc = $local->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
                $bookingLocal = $local->format('Y-m-d H:i');
            }

            if ((!$bookingDate || !$bookingTime) && $bookingUtc) {
                $utc = new \DateTimeImmutable($bookingUtc, new \DateTimeZone('UTC'));
                $local = $utc->setTimezone(new \DateTimeZone($timeZone));
                $bookingDate = $local->format('Y-m-d');
                $bookingTime = $local->format('H:i');
                $bookingLocal = $bookingLocal ?: $local->format('Y-m-d H:i');
            }
        } catch (\Exception $exception) {
            return back()->withErrors(['booking_time' => 'Unable to calculate booking date and time from the provided values.']);
        }

        if (empty($bookingDate) || empty($bookingTime)) {
            return back()->withErrors(['booking_time' => 'Please select a valid booking date and time.']);
        }

        try {
            // Compare the full selected date and time in the provided timezone
            $selectedDateTime = new \DateTimeImmutable("{$bookingDate} {$bookingTime}", new \DateTimeZone($timeZone));
            $now = new \DateTimeImmutable('now', new \DateTimeZone($timeZone));

            if ($selectedDateTime <= $now) {
                return back()->withErrors(['booking_time' => 'Please select a valid future date.']);
            }

            if ((int) $selectedDateTime->format('w') === 0) {
                return back()->withErrors(['booking_time' => 'Sunday bookings are not available. Please choose another day.']);
            }
        } catch (\Exception $exception) {
            return back()->withErrors(['booking_time' => 'Please select a valid booking date and time.']);
        }

        $philippineDateTime = $this->convertToPhilippineDateTime($bookingDate, $bookingTime, $timeZone);
        $bookingDate = $philippineDateTime['date'];
        $bookingTime = $philippineDateTime['time'];

        $exists = Booking::whereIn('status', $this->getActiveBookingStatuses())
            ->get()
            ->contains(function (Booking $booking) use ($bookingDate, $bookingTime): bool {
                $existingDateTime = $this->resolvePhilippineDateTime($booking);

                return $existingDateTime['date'] === $bookingDate && $existingDateTime['time'] === $bookingTime;
            });

        if ($exists) {
            return back()->withErrors(['booking_time' => 'That date and time is already booked. Please choose another slot.']);
        }

        Booking::create([
            ...$data,
            'booking_utc' => $bookingUtc,
            'booking_local' => $bookingLocal,
            'booking_date' => $bookingDate,
            'booking_time' => $bookingTime,
            'status' => 'pending',
            'meeting_duration' => '30 minutes',
        ]);

        return redirect()->back()->with('success', 'Thanks! Your booking request has been received.');
    }

    public function adminIndex(Request $request)
    {
        $query = Booking::query()->orderBy('created_at')->orderBy('booking_utc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhere('service', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('service')) {
            $query->where('service', $request->input('service'));
        }

        if ($request->filled('booking_date')) {
            $query->where('booking_date', $request->input('booking_date'));
        }

        if ($request->filled('booking_timezone')) {
            $query->where('booking_timezone', $request->input('booking_timezone'));
        }

        $bookings = $query->get();

        $bookings = $bookings->map(function ($booking) {
            // Friendly timezone label for display
            $booking->booking_timezone = $this->formatTimeZoneLabel($booking->booking_timezone);

            // booking_date & booking_time are stored as Philippine time (PHT)
            if ($booking->booking_date && $booking->booking_time) {
                $formattedPhtTime = date('g:i A', strtotime('1970-01-01 ' . $booking->booking_time));
                $booking->booking_time_ph = $booking->booking_date . ' ' . $formattedPhtTime;
            } else {
                $formattedPhtTime = $booking->booking_time ? date('g:i A', strtotime('1970-01-01 ' . $booking->booking_time)) : '--:--';
                $booking->booking_time_ph = $booking->booking_date ? ($booking->booking_date . ' ' . $formattedPhtTime) : 'Not scheduled';
            }

            // Preserve and expose the original local time (from booking_local) for admin display.
            if (!empty($booking->booking_local)) {
                // booking_local expected format: 'Y-m-d H:i'
                $booking->booking_original_time = date('D, M j · g:i A', strtotime($booking->booking_local));
            } elseif (!empty($booking->booking_date) && !empty($booking->booking_time)) {
                // Fallback to stored booking_date + booking_time when original local datetime is unavailable.
                $booking->booking_original_time = date('D, M j · g:i A', strtotime($booking->booking_date . ' ' . $booking->booking_time));
            } else {
                $booking->booking_original_time = $booking->booking_time ? date('g:i A', strtotime('1970-01-01 ' . $booking->booking_time)) : '--:--';
            }

            return $booking;
        });

        $stats = [
            'pending' => Booking::where('status', 'pending')->count(),
            'confirmed' => Booking::where('status', 'confirmed')->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'rescheduled' => Booking::where('status', 'rescheduled')->count(),
            'no_show' => Booking::where('status', 'no_show')->count(),
        ];

        return view('admin.bookings.index', compact('bookings', 'stats'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled,rescheduled,no_show'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
            'booking_date' => ['nullable', 'date'],
            'booking_time' => ['nullable', 'string', 'max:255'],
            'service' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'booking_timezone' => ['nullable', 'string', 'max:255'],
        ]);

        if (!empty($data['booking_date']) && !empty($data['booking_time'])) {
            $philippineDateTime = $this->convertToPhilippineDateTime($data['booking_date'], $data['booking_time'], $data['booking_timezone'] ?? 'Asia/Manila');
            $exists = Booking::where('id', '!=', $booking->id)
                ->whereIn('status', $this->getActiveBookingStatuses())
                ->get()
                ->contains(function (Booking $existingBooking) use ($philippineDateTime): bool {
                    $existingDateTime = $this->resolvePhilippineDateTime($existingBooking);

                    return $existingDateTime['date'] === $philippineDateTime['date'] && $existingDateTime['time'] === $philippineDateTime['time'];
                });

            if ($exists) {
                return back()->withErrors(['booking_time' => 'That date and time is already booked by another appointment.']);
            }
        }


        $data['booking_timezone'] = $this->normalizeTimeZone($data['booking_timezone'] ?? null);

        // If admin provided a booking_date and booking_time, persist both the original local
        // values and the converted Philippine date/time so the system remains consistent.
        if (!empty($data['booking_date']) && !empty($data['booking_time'])) {
            try {
                $normalized = $this->normalizeTimeZone($data['booking_timezone'] ?? 'Asia/Manila');
                $local = new \DateTimeImmutable("{$data['booking_date']} {$data['booking_time']}", new \DateTimeZone($normalized));
                $data['booking_utc'] = $local->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d\TH:i:s\Z');
                $data['booking_local'] = $local->format('Y-m-d H:i');

                $philippine = $this->convertToPhilippineDateTime($data['booking_date'], $data['booking_time'], $normalized);
                $data['booking_date'] = $philippine['date'];
                $data['booking_time'] = $philippine['time'];
            } catch (\Exception $e) {
                // If conversion fails, ignore and let validation handle any issues.
            }
        }

        $booking->update($data);

        return redirect()->back()->with('success', 'Booking status updated.');
    }

    public function show(Booking $booking)
    {
        $booking->display_booking_time = $booking->booking_time ? date('g:i A', strtotime('1970-01-01 ' . $booking->booking_time)) : null;

        return view('admin.bookings.show', compact('booking'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with('success', 'Booking deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $data = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:bookings,id'],
        ]);

        Booking::whereIn('id', $data['ids'])->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()->route('admin.bookings.index')->with('success', 'Selected bookings deleted.');
    }

    public function availability(Request $request)
    {
        $date = $request->query('booking_date');
        $service = $request->query('service', 'Discovery Call');
        $timezone = $this->normalizeTimeZone($request->query('booking_timezone', 'Asia/Manila'));
        $friendlyTimezone = $this->formatTimeZoneLabel($timezone);

        $bookedTimes = Booking::where('service', $service)
            ->whereIn('status', $this->getActiveBookingStatuses())
            ->get()
            ->filter(function (Booking $booking) use ($date, $timezone): bool {
                $philippineDateTime = $this->resolvePhilippineDateTime($booking);

                if (!$date || !$philippineDateTime['date'] || !$philippineDateTime['time']) {
                    return false;
                }

                $converted = $this->convertPhilippineDateTimeToTimezone($philippineDateTime['date'], $philippineDateTime['time'], $timezone);

                return $converted['date'] === $date;
            })
            ->map(function (Booking $booking) use ($timezone): string {
                $philippineDateTime = $this->resolvePhilippineDateTime($booking);
                $converted = $this->convertPhilippineDateTimeToTimezone($philippineDateTime['date'], $philippineDateTime['time'], $timezone);

                return substr($converted['time'], 0, 5);
            })
            ->values()
            ->all();

        $slots = [
            '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
        ];

        $available = array_values(array_filter($slots, fn ($slot) => !in_array($slot, $bookedTimes, true)));

        return response()->json([
            'timezone' => $friendlyTimezone,
            'date' => $date,
            'service' => $service,
            'available' => $available,
            'booked' => $bookedTimes,
            'times' => array_map(fn ($time) => ['time' => $time], $bookedTimes),
        ]);
    }

    public function getBookedTimes(Request $request)
    {
        $date = $request->query('date');
        $timezone = $request->query('timezone', 'Asia/Manila');

        if (!$date) {
            return response()->json(['booked_times' => []], 200);
        }

        $normalizedTimeZone = $this->normalizeTimeZone($timezone);

        $bookedTimes = Booking::whereIn('status', $this->getActiveBookingStatuses())
            ->get()
            ->filter(function (Booking $booking) use ($date, $normalizedTimeZone): bool {
                $philippineDateTime = $this->resolvePhilippineDateTime($booking);

                if (!$date || !$philippineDateTime['date'] || !$philippineDateTime['time']) {
                    return false;
                }

                $converted = $this->convertPhilippineDateTimeToTimezone($philippineDateTime['date'], $philippineDateTime['time'], $normalizedTimeZone);

                return $converted['date'] === $date;
            })
            ->map(function (Booking $booking) use ($normalizedTimeZone): string {
                $philippineDateTime = $this->resolvePhilippineDateTime($booking);
                $converted = $this->convertPhilippineDateTimeToTimezone($philippineDateTime['date'], $philippineDateTime['time'], $normalizedTimeZone);

                return substr($converted['time'], 0, 5);
            })
            ->values()
            ->all();

        return response()->json(['booked_times' => $bookedTimes], 200);
    }
}
