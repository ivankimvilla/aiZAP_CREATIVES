@extends('admin.layout')

@section('title', 'Bookings')

@section('content')
<link rel="stylesheet" href="{{ asset('css/admin/bookings.css') }}" />
<link rel="stylesheet" href="{{ asset('css/admin/bulk-actions.css') }}" />
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
                <span class="bk-stat-value">{{ $bookings->count() }}</span>
                <span class="bk-stat-label">Total</span>
            </div>
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

        <section class="bulk-toolbar" data-bulk-item-selector="tbody tr" data-empty-state-selector=".bk-empty">
            <label class="bulk-select">
                <input type="checkbox" class="bulk-select-all" aria-label="Select all bookings" />
                Select all
            </label>
            <div class="bulk-actions">
                <span class="bulk-selected-count">0 selected</span>
                <button type="button" class="btn-delete-selected" data-delete-url="{{ route('admin.bookings.bulk_destroy', [], false) }}" disabled>Delete selected</button>
            </div>
        </section>

        <section class="bk-panel">
            <div class="bk-table-wrap">
                <table class="bk-table">
                    <thead>
                        <tr>
                            <th></th>
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
                                <td>
                                    <input type="checkbox" class="bulk-item-checkbox" data-item-id="{{ $booking->id }}" aria-label="Select booking #{{ $booking->id }}" />
                                </td>
                                <td class="bk-id">#{{ $booking->id }}</td>
                                <td>
                                    <p class="bk-cust-name">{{ $booking->name }}</p>
                                    <a href="mailto:{{ $booking->email }}" class="bk-cust-email">{{ $booking->email }}</a>
                                </td>
                                <td>{{ $booking->service ?? 'Discovery Call' }}</td>
                                <td class="bk-pht">{{ $booking->booking_time_ph }}</td>
                                <td class="bk-orig">
                                    {{ $booking->booking_original_time ?? ($booking->booking_local ? date('g:i A', strtotime($booking->booking_local)) : '--:--') }}
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

    @push('scripts')
        <script type="module">
            import adminBulkDelete from '/js/admin/bulk-delete.js';
            try { adminBulkDelete(); } catch (e) { console.error('adminBulkDelete init failed', e); }
        </script>
    @endpush
@endsection