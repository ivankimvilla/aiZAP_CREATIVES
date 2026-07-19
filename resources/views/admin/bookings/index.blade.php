@extends('admin.layout')

@section('title', 'Bookings')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/bookings.css') }}" />
    <main class="bk-page">
        <section class="bk-hero">
            <h1 class="hero-title">Bookings</h1>
            <p class="hero-sub">Manage and update booking requests without leaving the admin dashboard.</p>
        </section>

        <div class="bk-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <path d="M12 16v-4"></path>
                <path d="M12 8h.01"></path>
            </svg>
            <p>All booking times are automatically converted to <strong>Philippine Standard Time (PHT)</strong> for easy tracking. The original timezone and time are shown separately for reference.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <span class="alert-icon">&#10003;</span>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <span class="alert-icon">!</span>
                <div>
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        <section class="bk-stats">
            <div class="bk-stat">
                <span class="bk-stat-value">{{ $stats['pending'] }}</span>
                <span class="bk-stat-label">Pending</span>
            </div>
            <div class="bk-stat">
                <span class="bk-stat-value">{{ $stats['confirmed'] }}</span>
                <span class="bk-stat-label">Confirmed</span>
            </div>
            <div class="bk-stat">
                <span class="bk-stat-value">{{ $stats['completed'] }}</span>
                <span class="bk-stat-label">Completed</span>
            </div>
            <div class="bk-stat">
                <span class="bk-stat-value">{{ $stats['cancelled'] }}</span>
                <span class="bk-stat-label">Cancelled</span>
            </div>
            <div class="bk-stat">
                <span class="bk-stat-value">{{ $stats['rescheduled'] }}</span>
                <span class="bk-stat-label">Rescheduled</span>
            </div>
            <div class="bk-stat">
                <span class="bk-stat-value">{{ $stats['no_show'] }}</span>
                <span class="bk-stat-label">No Show</span>
            </div>
        </section>

        <section class="bk-panel">
            <form method="get" class="bk-filters">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email, ID, service" />
                <input type="date" name="booking_date" value="{{ request('booking_date') }}" />
                <select name="status">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                    <option value="rescheduled" @selected(request('status') === 'rescheduled')>Rescheduled</option>
                    <option value="no_show" @selected(request('status') === 'no_show')>No Show</option>
                </select>
                <input type="text" name="service" value="{{ request('service') }}" placeholder="Service" />
                <input type="text" name="booking_timezone" value="{{ request('booking_timezone') }}" placeholder="Time zone" />
                <button type="submit" class="btn-primary">Filter</button>
            </form>

            <div class="bk-table-wrap">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Date/Time (PHT)</th>
                            <th>Original time</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bookings as $booking)
                            <tr>
                                <td class="bk-id">#{{ $booking->id }}</td>
                                <td>
                                    <p class="bk-cust-name">{{ $booking->name }}</p>
                                    <a href="mailto:{{ $booking->email }}" class="bk-cust-email">{{ $booking->email }}</a>
                                </td>
                                <td>{{ $booking->service ?? 'Discovery Call' }}</td>
                                <td class="bk-pht">{{ $booking->booking_time_ph }}</td>
                                <td class="bk-orig">
                                    {{ date('g:i A', strtotime('1970-01-01 ' . $booking->booking_time)) }}
                                    <span class="bk-tz">{{ $booking->booking_timezone }}</span>
                                </td>
                                <td>
                                    <span class="bk-status status-{{ \Illuminate\Support\Str::slug($booking->status) }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="bk-actions">
                                        <a href="{{ route('admin.bookings.show', $booking) }}" class="bk-btn bk-btn-view">View</a>

                                        <form method="post" action="{{ route('admin.bookings.status.update', $booking) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="confirmed" />
                                            <button type="submit" class="bk-btn bk-btn-confirm">Confirm</button>
                                        </form>

                                        <form method="post" action="{{ route('admin.bookings.status.update', $booking) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="completed" />
                                            <button type="submit" class="bk-btn bk-btn-done">Done</button>
                                        </form>

                                        <form method="post" action="{{ route('admin.bookings.status.update', $booking) }}">
                                            @csrf
                                            <input type="hidden" name="status" value="cancelled" />
                                            <button type="submit" class="bk-btn bk-btn-cancel">Cancel</button>
                                        </form>

                                        <form method="post" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Delete this booking? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bk-btn-delete" title="Delete booking">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"></path>
                                                    <path d="M10 11v6"></path>
                                                    <path d="M14 11v6"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="bk-empty">No bookings match your filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>
@endsection