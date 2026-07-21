@extends('admin.layout')

@section('title', 'Booking Details')

@section('content')
 <link rel="stylesheet" href="{{ asset('css/admin/bookings-view.css') }}" />
    <main class="admin-booking-page">
        <div class="admin-booking-topbar">
            <a href="{{ route('admin.bookings.index') }}" class="admin-back-btn">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
                Back to bookings
            </a>
        </div>

        <section class="admin-booking-hero">
            <h1>Booking Details</h1>
            <p class="admin-hero-sub">Review and update the booking without leaving the sidebar menu.</p>
        </section>

        @if (session('success'))
            <div class="alert-dash alert-dash-success">
                <span class="alert-dash-icon">✓</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-booking-card">
            <div class="admin-booking-card-header">
                <div>
                    <span class="admin-booking-id">Booking #{{ $booking->id }}</span>
                    <h2>{{ $booking->name }}</h2>
                </div>
                <span class="admin-status-pill admin-status-{{ Str::slug($booking->status) }}">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
            </div>

            <div class="admin-booking-details-grid">
                <div class="admin-booking-detail">
                    <span class="admin-detail-label">Email</span>
                    <span class="admin-detail-value">{{ $booking->email }}</span>
                </div>
                <div class="admin-booking-detail">
                    <span class="admin-detail-label">Phone</span>
                    <span class="admin-detail-value">{{ $booking->phone }}</span>
                </div>
                <div class="admin-booking-detail">
                    <span class="admin-detail-label">Service</span>
                    <span class="admin-detail-value">{{ $booking->service ?? 'Discovery Call' }}</span>
                </div>
                <div class="admin-booking-detail">
                    <span class="admin-detail-label">Time zone</span>
                    <span class="admin-detail-value">{{ $booking->booking_timezone }}</span>
                </div>
                <div class="admin-booking-detail">
                    <span class="admin-detail-label">Booking date (PHT)</span>
                    <span class="admin-detail-value">{{ $booking->booking_date }}</span>
                </div>
                <div class="admin-booking-detail">
                    <span class="admin-detail-label">Booking time (PHT)</span>
                    <span class="admin-detail-value">{{ $booking->display_booking_time ?? date('g:i A', strtotime('1970-01-01 ' . $booking->booking_time)) }}</span>
                </div>
                <div class="admin-booking-detail">
                    <span class="admin-detail-label">Original time</span>
                    <span class="admin-detail-value">{{ $booking->booking_original_time ?? ($booking->booking_local ? date('g:i A', strtotime($booking->booking_local)) : '--:--') }}</span>
                </div>
            </div>

            <div class="admin-booking-notes">
                <span class="admin-detail-label">Notes</span>
                <p>{{ $booking->notes ?? $booking->message ?? 'No notes provided.' }}</p>
            </div>

            <div class="admin-booking-divider"></div>

            <form method="post" action="{{ route('admin.bookings.status.update', $booking) }}" class="admin-booking-form">
                @csrf

                <div class="admin-form-row">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="pending" @selected($booking->status === 'pending')>Pending</option>
                        <option value="confirmed" @selected($booking->status === 'confirmed')>Confirmed</option>
                        <option value="completed" @selected($booking->status === 'completed')>Completed</option>
                        <option value="cancelled" @selected($booking->status === 'cancelled')>Cancelled</option>
                        <option value="rescheduled" @selected($booking->status === 'rescheduled')>Rescheduled</option>
                        <option value="no_show" @selected($booking->status === 'no_show')>No Show</option>
                    </select>
                </div>

                <div class="admin-form-row-split">
                    <div class="admin-form-row">
                        <label for="booking_date">Booking date</label>
                        <input id="booking_date" type="date" name="booking_date" value="{{ old('booking_date', $booking->booking_date) }}" />
                    </div>
                    <div class="admin-form-row">
                        <label for="booking_time">Booking time</label>
                        <input id="booking_time" type="text" name="booking_time" value="{{ old('booking_time', $booking->booking_time) }}" placeholder="Booking time" />
                    </div>
                </div>

                <div class="admin-form-row">
                    <label for="booking_timezone">Time zone</label>
                    <input id="booking_timezone" type="text" name="booking_timezone" value="{{ old('booking_timezone', $booking->booking_timezone) }}" placeholder="Time zone" />
                </div>

                <div class="admin-form-row">
                    <label for="notes">Notes</label>
                    <textarea id="notes" name="notes" rows="3" placeholder="Notes">{{ old('notes', $booking->notes) }}</textarea>
                </div>

                <div class="admin-form-row">
                    <label for="admin_notes">Admin notes</label>
                    <textarea id="admin_notes" name="admin_notes" rows="3" placeholder="Admin notes">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
                </div>

                <div class="admin-form-actions">
                    <button type="submit" class="btn-sm admin-btn-save">Save changes</button>
                </div>
            </form>
        </div>
    </main>
@endsection
