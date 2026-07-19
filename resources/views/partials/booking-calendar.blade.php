<link rel="stylesheet" href="{{ asset('css/booking-calendar.css') }}">

@if(session('success'))
    <div class="booking-success-notification">
        <div class="success-content">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <span>{{ session('success') }}</span>
            <button type="button" class="success-close" onclick="this.parentElement.parentElement.style.display='none';">×</button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const notification = document.querySelector('.booking-success-notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }, 5000);
    </script>
@endif

@if($errors->any())
    <div class="booking-error-notification" role="alert">
        <div class="success-content">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ $errors->first() }}</span>
            <button type="button" class="success-close" onclick="this.parentElement.parentElement.style.display='none';">×</button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const notification = document.querySelector('.booking-error-notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }, 5000);
    </script>
@endif

@if(session('success') || $errors->any())
    <style>body { overflow: hidden !important; }</style>
@endif

@if($errors->any())
    <div class="booking-error-notification" role="alert">
        <div class="success-content">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
            <span>{{ $errors->first() }}</span>
            <button type="button" class="success-close" onclick="this.parentElement.parentElement.style.display='none';">×</button>
        </div>
    </div>
    <script>
        setTimeout(() => {
            const notification = document.querySelector('.booking-error-notification');
            if (notification) {
                notification.style.display = 'none';
            }
        }, 5000);
    </script>
@endif

<div class="booking-calendar-container @if(session('success') || $errors->any()) open @endif" id="bookingCalendar" aria-hidden="@if(session('success') || $errors->any())false @else true @endif">
    <div class="booking-calendar-overlay"></div>

    <div class="booking-calendar-modal">
        <button type="button" class="booking-calendar-close" aria-label="Close calendar">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>

        <div class="booking-calendar-left">
            <div class="booking-header">
                <div class="booking-logo">
                    <span class="logo-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8z"/></svg>
                    </span>
                    <span>aiZap Creative</span>
                </div>
                <h3 class="booking-title">Discovery Call</h3>
                <p class="booking-meta">
                    <span class="booking-duration">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg>
                        30 minutes
                    </span>
                </p>
                <p class="booking-subtitle">Schedule a free call with us</p>
            </div>

            <div class="calendar-timezone">
                <div class="timezone-picker">
                    <label class="timezone-picker-label" for="timezoneSearch">Your time zone</label>
                    <div class="timezone-selector" id="timezoneSelector" role="combobox" aria-expanded="false" aria-controls="timezoneDropdown">
                        <span class="timezone-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.5 3.8 5.7 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.7-3.8-9s1.3-6.5 3.8-9z"/></svg>
                        </span>
                        <input type="text" id="timezoneSearch" placeholder="Search or select a timezone" autocomplete="off" aria-label="Search or select a timezone">
                        <span class="timezone-caret" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m6 9 6 6 6-6"/></svg>
                        </span>
                    </div>
                    <div class="timezone-selected-info" id="timezoneSelectedLabel" aria-live="polite"></div>
                    <div class="timezone-dropdown" id="timezoneDropdown" hidden></div>
                    <input type="hidden" id="selectedTimezone" value="">
                    <input type="hidden" id="selectedBookingUtc" value="">
                    <input type="hidden" id="selectedBookingLocal" value="">
                </div>
            </div>

            <div class="calendar-widget">
                <div class="calendar-header">
                    <button type="button" class="calendar-nav-btn calendar-prev" aria-label="Previous month">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <h4 class="calendar-month" id="calendarMonth">July</h4>
                    <button type="button" class="calendar-nav-btn calendar-next" aria-label="Next month">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>

                <div class="calendar-grid">
                    <div class="calendar-weekdays">
                        <div class="weekday">SUN</div>
                        <div class="weekday">MON</div>
                        <div class="weekday">TUE</div>
                        <div class="weekday">WED</div>
                        <div class="weekday">THU</div>
                        <div class="weekday">FRI</div>
                        <div class="weekday">SAT</div>
                    </div>

                    <div class="calendar-dates" id="calendarDates">
                        <!-- Generated by JavaScript -->
                    </div>
                </div>

                <div class="calendar-footer">
                    <span class="calendar-unavailable-note">Sundays are unavailable for booking.</span>
                    <span class="powered-by">Powered by <strong>aiZAPCal</strong></span>
                </div>
            </div>
        </div>

        <div class="booking-calendar-right">
            <div class="booking-date-display" id="bookingDateDisplay">
                <h5 class="booking-day-name">Select a date &amp; time</h5>
                <p class="booking-day-num"></p>
            </div>
            <div class="booking-unavailable-message" id="bookingUnavailableMessage" hidden>Sundays are unavailable for booking.</div>

            <div class="booking-slots">
                <span class="booking-times-label">Available times</span>
                <div class="booking-times" id="bookingTimes" data-booked-times="[]">
                    <div class="time-slot">12:00 PM</div>
                    <div class="time-slot">12:30 PM</div>
                    <div class="time-slot">1:00 PM</div>
                    <div class="time-slot">1:30 PM</div>
                    <div class="time-slot">2:00 PM</div>
                    <div class="time-slot">2:30 PM</div>
                    <div class="time-slot">3:00 PM</div>
                    <div class="time-slot">5:00 PM</div>
                </div>
            </div>

            <form class="booking-form" method="post" action="{{ route('bookings.store') }}">
                @csrf
                <input type="hidden" name="booking_utc" id="bookingUtcInput" value="{{ old('booking_utc') }}">
                <input type="hidden" name="booking_local" id="bookingLocalInput" value="{{ old('booking_local') }}">
                <input type="hidden" name="booking_timezone" id="bookingTimezoneInput" value="{{ old('booking_timezone', 'Asia/Manila') }}">
                <input type="hidden" name="booking_date" id="bookingDateInput" value="{{ old('booking_date') }}">
                <input type="hidden" name="booking_time" id="bookingTimeInput" value="{{ old('booking_time') }}">
                <input type="hidden" name="service" id="serviceInput" value="{{ old('service', 'Discovery Call') }}">
                <input type="hidden" name="request_type" value="book_call">

                <div id="bookingFormError" class="booking-form-error" hidden></div>

                <div class="booking-form-fields">
                    <input type="text" name="name" class="booking-input" placeholder="Your name" value="{{ old('name') }}" required>
                    <input type="email" name="email" class="booking-input" placeholder="Your email" value="{{ old('email') }}" required>

                    <div class="booking-form-row">
                        <input type="text" name="phone" class="booking-input" placeholder="Phone (optional)" value="{{ old('phone') }}">
                    </div>

                    <textarea name="message" rows="3" class="booking-textarea" placeholder="Tell us about your project">{{ old('message') }}</textarea>
                </div>

                <div class="booking-form-footer">
                    <button type="submit" class="btn btn-primary booking-submit">Save Booking</button>
                </div>
            </form>
        </div>
    </div>
</div>