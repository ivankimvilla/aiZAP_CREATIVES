export function initBookingCalendar() {
    function pad(n) { return String(n).padStart(2, '0'); }

    function getTimeZoneOffsetMinutes(timeZone, date) {
        try {
            const formatter = new Intl.DateTimeFormat('en-US', {
                timeZone,
                timeZoneName: 'shortOffset',
            });
            const parts = formatter.formatToParts(date);
            const zoneName = parts.find((part) => part.type === 'timeZoneName')?.value || 'GMT';
            const offsetMatch = zoneName.match(/GMT([+-])(\d{1,2})(?::?(\d{2}))?/);
            if (!offsetMatch) return 0;
            const sign = offsetMatch[1] === '-' ? -1 : 1;
            const hours = Number(offsetMatch[2] || 0);
            const minutes = Number(offsetMatch[3] || 0);
            return sign * ((hours * 60) + minutes);
        } catch (e) {
            return 0;
        }
    }

    function getUtcTimestampForSelection(day, monthIndex, yearNumber, hour, minute, timeZone = 'Asia/Manila') {
        const baseDate = new Date(Date.UTC(yearNumber, monthIndex, day, hour, minute));
        const offsetMinutes = getTimeZoneOffsetMinutes(timeZone, baseDate);
        return new Date(baseDate.getTime() - (offsetMinutes * 60 * 1000));
    }

    function fetchBookedTimes(date, timezone) {
        const bookingTimes = document.getElementById('bookingTimes');
        if (!bookingTimes) return;

        const service = document.getElementById('serviceInput')?.value || 'Discovery Call';
        const tzForRequest = document.getElementById('bookingTimezoneInput')?.value
            || document.getElementById('selectedTimezone')?.value
            || timezone
            || 'Asia/Manila';
        const url = `/bookings/availability?booking_date=${encodeURIComponent(date)}&booking_timezone=${encodeURIComponent(tzForRequest)}&service=${encodeURIComponent(service)}`;
        fetch(url)
            .then(response => response.json())
            .then((data) => {
                bookingTimes.dataset.bookedTimes = JSON.stringify(data.booked || []);
                bookingTimes.dataset.availableTimes = JSON.stringify(data.available || []);
                updateTimeSlotStates();
            })
            .catch((error) => {
                console.error('Error fetching booked times:', error);
                bookingTimes.dataset.bookedTimes = JSON.stringify([]);
                bookingTimes.dataset.availableTimes = JSON.stringify([]);
                updateTimeSlotStates();
            });
    }

    function updateTimeSlotStates() {
        const bookingTimes = document.getElementById('bookingTimes');
        if (!bookingTimes) return;

        const availableTimes = new Set((JSON.parse(bookingTimes.dataset.availableTimes || '[]')).map((time) => String(time).slice(0, 5)));

        document.querySelectorAll('.time-slot').forEach((slot) => {
            let originalTime = slot.dataset.originalTime;
            if (!originalTime) {
                originalTime = slot.textContent.replace(/booked/gi, '').trim();
                slot.dataset.originalTime = originalTime;
            }

            const match = originalTime.match(/(\d+):(\d+)\s*(AM|PM)?/i);
            if (!match) return;

            const [, hours, minutes, period] = match;
            let hour = parseInt(hours, 10);
            if (period && period.toUpperCase() === 'PM' && hour !== 12) {
                hour += 12;
            } else if (period && period.toUpperCase() === 'AM' && hour === 12) {
                hour = 0;
            }
            const time24 = String(hour).padStart(2, '0') + ':' + minutes;
            const isAvailable = availableTimes.has(time24);

            if (!isAvailable) {
                slot.classList.add('disabled');
                slot.setAttribute('data-available', 'false');
                slot.innerHTML = `${originalTime}<br><span class="time-slot-unavailable">Booked</span>`;
            } else {
                slot.classList.remove('disabled');
                slot.setAttribute('data-available', 'true');
                slot.innerHTML = originalTime;
            }
        });
    }

    const bookingCalendar = document.getElementById('bookingCalendar');
    if (!bookingCalendar || bookingCalendar.dataset.calendarBound === '1') return;
    bookingCalendar.dataset.calendarBound = '1';

    const closeBtn = document.querySelector('.booking-calendar-close');
    const toggles = document.querySelectorAll('[data-request-type="book_call"]');
    const timezoneInput = document.getElementById('timezoneSearch');
    const timezoneDropdown = document.getElementById('timezoneDropdown');
    const timezoneSelector = document.getElementById('timezoneSelector');
    const timezoneSelectedLabel = document.getElementById('timezoneSelectedLabel');
    const selectedTimezoneInput = document.getElementById('selectedTimezone');
    const bookingTimezoneInput = document.getElementById('bookingTimezoneInput');
    const bookingUnavailableMessage = document.querySelector('.booking-unavailable-message');
    const bookingForm = document.querySelector('.booking-form');
    const bookingSlots = document.querySelector('.booking-slots');
    const bookingFormFooter = document.querySelector('.booking-form-footer');
    const calendarErrorMessage = document.getElementById('calendarErrorMessage');
    const bookingFormErrorEl = document.querySelector('.booking-form-error');

    const timeZoneOptions = [
        { label: 'Philippine Standard Time (PHT)', value: 'Asia/Manila', description: 'Philippines' },
        { label: 'Pacific Time (PT)', value: 'America/Los_Angeles', description: 'USA (Los Angeles, Seattle)' },
        { label: 'Mountain Time (MT)', value: 'America/Denver', description: 'USA (Denver)' },
        { label: 'Central Time (CT)', value: 'America/Chicago', description: 'USA (Chicago, Dallas)' },
        { label: 'Eastern Time (ET)', value: 'America/New_York', description: 'USA (New York, Miami)' },
        { label: 'UTC / GMT', value: 'UTC', description: 'Coordinated Universal Time' },
        { label: 'Central European Time (CET/CEST)', value: 'Europe/Paris', description: 'Germany, France, Italy, Spain' },
        { label: 'Eastern European Time (EET/EEST)', value: 'Europe/Athens', description: 'Greece, Finland, Poland' },
        { label: 'Gulf Standard Time (GST)', value: 'Asia/Dubai', description: 'UAE, Dubai, Saudi Arabia' },
        { label: 'India Standard Time (IST)', value: 'Asia/Kolkata', description: 'India' },
        { label: 'Singapore Standard Time (SGT)', value: 'Asia/Singapore', description: 'Singapore' },
        { label: 'Hong Kong Standard Time (HKT)', value: 'Asia/Hong_Kong', description: 'Hong Kong' },
        { label: 'China Standard Time (CST)', value: 'Asia/Shanghai', description: 'China, Mongolia' },
        { label: 'Japan Standard Time (JST)', value: 'Asia/Tokyo', description: 'Japan' },
        { label: 'Korea Standard Time (KST)', value: 'Asia/Seoul', description: 'South Korea, North Korea' },
        { label: 'Australian Eastern Time (AET)', value: 'Australia/Sydney', description: 'Sydney, Melbourne, Brisbane' }
    ];

    const getDetectedTimeZone = () => {
        if (typeof Intl !== 'undefined' && Intl.DateTimeFormat && Intl.DateTimeFormat().resolvedOptions) {
            const detectedZone = Intl.DateTimeFormat().resolvedOptions().timeZone;
            if (detectedZone) return detectedZone;
        }
        return 'Asia/Manila';
    };

    const setSelectedTimeZone = (zone, label) => {
        const selectedOption = timeZoneOptions.find((entry) => entry.value === zone) || timeZoneOptions[0];
        const optionLabel = label || selectedOption?.label || zone;
        const optionValue = zone || selectedOption?.value || 'Asia/Manila';

        if (timezoneInput) timezoneInput.value = optionLabel;
        if (selectedTimezoneInput) selectedTimezoneInput.value = optionValue;
        if (bookingTimezoneInput) bookingTimezoneInput.value = optionValue;
        if (timezoneSelectedLabel) timezoneSelectedLabel.textContent = optionLabel;
        if (timezoneDropdown) timezoneDropdown.hidden = true;
        if (timezoneSelector) timezoneSelector.setAttribute('aria-expanded', 'false');

        const bookingDateInput = document.getElementById('bookingDateInput');
        if (bookingDateInput && bookingDateInput.value) {
            fetchBookedTimes(bookingDateInput.value, optionValue);
        }
    };

    const renderTimezoneOptions = (filter = '') => {
        if (!timezoneDropdown) return;
        const normalizedFilter = filter.trim().toLowerCase();
        const matches = timeZoneOptions.filter((entry) => {
            if (!normalizedFilter) return true;
            return entry.label.toLowerCase().includes(normalizedFilter)
                || entry.value.toLowerCase().includes(normalizedFilter)
                || entry.description.toLowerCase().includes(normalizedFilter);
        });

        timezoneDropdown.innerHTML = '';
        if (!matches.length) {
            const emptyOption = document.createElement('div');
            emptyOption.className = 'timezone-option';
            emptyOption.textContent = 'No matching time zones';
            timezoneDropdown.appendChild(emptyOption);
            return;
        }

        matches.forEach((entry) => {
            const option = document.createElement('button');
            option.type = 'button';
            option.className = 'timezone-option';
            option.dataset.value = entry.value;
            option.dataset.label = entry.label;
            option.innerHTML = `<span class="timezone-option-name">${entry.label}</span><span class="timezone-option-location">${entry.description}</span>`;
            option.addEventListener('click', () => {
                setSelectedTimeZone(entry.value, entry.label);
                if (timezoneDropdown) timezoneDropdown.hidden = true;
                if (timezoneSelector) timezoneSelector.setAttribute('aria-expanded', 'false');
            });
            timezoneDropdown.appendChild(option);
        });
    };

    const openCalendar = () => {
        bookingCalendar.classList.add('open');
        bookingCalendar.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        if (timezoneDropdown) timezoneDropdown.hidden = true;
        if (timezoneSelector) timezoneSelector.setAttribute('aria-expanded', 'false');
        if (timezoneInput) {
            timezoneInput.value = timezoneInput.value || 'Asia/Manila';
        }
    };

    const closeCalendar = () => {
        bookingCalendar.classList.remove('open');
        bookingCalendar.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (timezoneDropdown) timezoneDropdown.hidden = true;
        if (timezoneSelector) timezoneSelector.setAttribute('aria-expanded', 'false');
    };

    toggles.forEach((toggle) => {
        toggle.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            openCalendar();
        });
    });

    if (closeBtn) {
        closeBtn.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            closeCalendar();
        });
    }
    const overlay = document.querySelector('.booking-calendar-overlay');
    if (overlay) {
        overlay.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            closeCalendar();
        });
    }

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && bookingCalendar.classList.contains('open')) {
            closeCalendar();
        }
    });

    if (timezoneSelector) {
        timezoneSelector.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            if (timezoneDropdown) {
                timezoneDropdown.hidden = false;
                timezoneSelector.setAttribute('aria-expanded', 'true');
            }
            renderTimezoneOptions('');
        });
    }

    if (timezoneInput) {
        timezoneInput.addEventListener('focus', () => {
            if (timezoneDropdown) {
                timezoneDropdown.hidden = false;
                timezoneSelector?.setAttribute('aria-expanded', 'true');
            }
            renderTimezoneOptions(timezoneInput.value);
        });
        timezoneInput.addEventListener('input', () => {
            if (timezoneDropdown) {
                timezoneDropdown.hidden = false;
                timezoneSelector?.setAttribute('aria-expanded', 'true');
            }
            renderTimezoneOptions(timezoneInput.value);
        });
        timezoneInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') {
                event.preventDefault();
                const firstOption = timezoneDropdown?.querySelector('.timezone-option');
                if (firstOption) {
                    firstOption.click();
                }
            }
        });
    }

    if (timezoneDropdown) {
        timezoneDropdown.addEventListener('click', (event) => {
            const option = event.target.closest('.timezone-option');
            if (!option) return;
            const zone = option.dataset.value;
            const label = option.dataset.label || option.textContent;
            setSelectedTimeZone(zone, label);
            timezoneDropdown.hidden = true;
            if (timezoneSelector) timezoneSelector.setAttribute('aria-expanded', 'false');
        });
    }

    document.addEventListener('click', (event) => {
        if (!timezoneSelector || !timezoneDropdown) return;
        const clickedInside = timezoneSelector.contains(event.target) || timezoneDropdown.contains(event.target);
        if (!clickedInside) {
            timezoneDropdown.hidden = true;
            timezoneSelector.setAttribute('aria-expanded', 'false');
        }
    });

    const today = new Date();
    const calendarDates = document.getElementById('calendarDates');
    const calendarMonth = document.getElementById('calendarMonth');
    const prevMonthBtn = document.querySelector('.calendar-prev');
    const nextMonthBtn = document.querySelector('.calendar-next');
    const calendarViewDate = new Date(today.getFullYear(), today.getMonth(), 1);

    const renderCalendar = (viewDate) => {
        const year = viewDate.getFullYear();
        const month = viewDate.getMonth();
        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const daysInPrevMonth = new Date(year, month, 0).getDate();
        const bookingDateInput = document.getElementById('bookingDateInput');
        const selectedDateValue = bookingDateInput?.value || '';

        if (calendarMonth) {
            calendarMonth.textContent = viewDate.toLocaleString('en-US', { month: 'long', year: 'numeric' });
        }

        if (!calendarDates) return;

        calendarDates.innerHTML = '';
        for (let index = firstDay - 1; index >= 0; index -= 1) {
            const date = document.createElement('div');
            date.className = 'calendar-date disabled';
            date.textContent = daysInPrevMonth - index;
            calendarDates.appendChild(date);
        }

        for (let day = 1; day <= daysInMonth; day += 1) {
            const date = document.createElement('div');
            date.className = 'calendar-date';
            if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                date.classList.add('today');
            }
            const dateStr = year + '-' + String(month + 1).padStart(2, '0') + '-' + String(day).padStart(2, '0');
            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = dayNames[new Date(year, month, day).getDay()];
            const isSunday = dayName === 'Sunday';
            if (isSunday) {
                date.classList.add('disabled', 'sunday-unavailable');
                date.setAttribute('aria-disabled', 'true');
                date.tabIndex = -1;
                date.setAttribute('title', 'Sundays are unavailable for booking.');
            }
            if (selectedDateValue && selectedDateValue === dateStr && !isSunday) {
                date.classList.add('selected');
                const display = document.getElementById('bookingDateDisplay');
                if (display) {
                    display.innerHTML = `<h5 class="booking-day-name">${dayName}</h5><p class="booking-day-num">${day}</p>`;
                }
            }
            if (isSunday) {
                date.innerHTML = `<span class="calendar-day-number">${day}</span><span class="calendar-day-label">SUN</span>`;
            } else {
                date.textContent = day;
            }
            if (!isSunday) {
                date.addEventListener('click', () => {
                    document.querySelectorAll('.calendar-date').forEach((item) => item.classList.remove('selected'));
                    date.classList.add('selected');
                    const display = document.getElementById('bookingDateDisplay');
                    if (display) {
                        display.innerHTML = `<h5 class="booking-day-name">${dayName}</h5><p class="booking-day-num">${day}</p>`;
                    }

                    const selectedDateObj = new Date(year, month, day);
                    const todayOnly = new Date(today.getFullYear(), today.getMonth(), today.getDate());
                    const isPast = selectedDateObj < todayOnly;

                    if (isPast) {
                        if (calendarErrorMessage) {
                            calendarErrorMessage.textContent = 'Please Select a Valid Date';
                            calendarErrorMessage.hidden = false;
                        }
                        if (bookingFormErrorEl) {
                            bookingFormErrorEl.hidden = true;
                            bookingFormErrorEl.textContent = '';
                        }
                        if (bookingDateInput) {
                            bookingDateInput.value = dateStr;
                        }
                        return;
                    }

                    if (calendarErrorMessage) {
                        calendarErrorMessage.hidden = true;
                        calendarErrorMessage.textContent = '';
                    }
                    if (bookingFormErrorEl) {
                        bookingFormErrorEl.hidden = true;
                        bookingFormErrorEl.textContent = '';
                    }

                    if (bookingDateInput) {
                        bookingDateInput.value = dateStr;
                    }
                    document.querySelectorAll('.time-slot').forEach((item) => item.classList.remove('selected'));
                    const bookingTimeInput = document.getElementById('bookingTimeInput');
                    if (bookingTimeInput) {
                        bookingTimeInput.value = '';
                    }
                    const timezoneInput = document.getElementById('bookingTimezoneInput');
                    const timezone = timezoneInput ? timezoneInput.value : 'Asia/Manila';
                    fetchBookedTimes(dateStr, timezone);
                });
            } else {
                date.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    const display = document.getElementById('bookingDateDisplay');
                    if (display) {
                        display.innerHTML = `<h5 class="booking-day-name">Sundays are unavailable for booking.</h5><p class="booking-day-num"></p>`;
                    }
                    if (bookingUnavailableMessage) {
                        bookingUnavailableMessage.hidden = false;
                    }
                    if (bookingForm) {
                        bookingForm.hidden = true;
                    }
                    if (bookingSlots) {
                        bookingSlots.hidden = true;
                    }
                    if (bookingFormFooter) {
                        bookingFormFooter.hidden = true;
                    }
                    if (bookingDateInput) {
                        bookingDateInput.value = '';
                    }
                    const bookingTimeInput = document.getElementById('bookingTimeInput');
                    if (bookingTimeInput) {
                        bookingTimeInput.value = '';
                    }
                });
            }
            calendarDates.appendChild(date);
        }

        const totalCells = calendarDates.children.length;
        const remainingCells = 42 - totalCells;
        for (let day = 1; day <= remainingCells; day += 1) {
            const date = document.createElement('div');
            date.className = 'calendar-date disabled';
            date.setAttribute('aria-disabled', 'true');
            date.tabIndex = -1;
            date.style.pointerEvents = 'none';
            date.textContent = day;
            calendarDates.appendChild(date);
        }
    };

    if (prevMonthBtn) {
        prevMonthBtn.addEventListener('click', () => {
            calendarViewDate.setMonth(calendarViewDate.getMonth() - 1);
            renderCalendar(calendarViewDate);
        });
    }

    if (nextMonthBtn) {
        nextMonthBtn.addEventListener('click', () => {
            calendarViewDate.setMonth(calendarViewDate.getMonth() + 1);
            renderCalendar(calendarViewDate);
        });
    }

    renderCalendar(calendarViewDate);

    const attachTimeSlotHandlers = () => {
        document.querySelectorAll('.time-slot').forEach((slot) => {
            slot.removeEventListener('click', handleTimeSlotClick);
            slot.addEventListener('click', handleTimeSlotClick);
        });
    };

    const restoreSelectedTimeSlot = () => {
        const bookingTimeInput = document.getElementById('bookingTimeInput');
        if (!bookingTimeInput || !bookingTimeInput.value) return;

        document.querySelectorAll('.time-slot').forEach((slot) => {
            const slotText = slot.textContent.trim().split('\n')[0];
            if (!slotText) return;
            const match = slotText.match(/(\d+):(\d+)\s*(AM|PM)?/i);
            if (!match) return;

            const [hours, minutes, period] = match.slice(1);
            let hour = parseInt(hours, 10);
            if (period && period.toUpperCase() === 'PM' && hour !== 12) {
                hour += 12;
            } else if (period && period.toUpperCase() === 'AM' && hour === 12) {
                hour = 0;
            }
            const normalizedValue = String(hour).padStart(2, '0') + ':' + minutes;
            if (normalizedValue === bookingTimeInput.value) {
                slot.classList.add('selected');
            }
        });
    };

    const handleTimeSlotClick = (event) => {
        const slot = event.currentTarget;
        const isBooked = slot.classList.contains('disabled') || slot.getAttribute('data-available') === 'false';
        if (isBooked) {
            return;
        }

        const originalTime = (slot.dataset.originalTime || slot.textContent || '').trim().split('\n')[0];
        const match = originalTime.match(/(\d+):(\d+)\s*(AM|PM)?/i);
        if (!match) return;
        const [hours, minutes, period] = match.slice(1);
        let hour = parseInt(hours, 10);
        if (period && period.toUpperCase() === 'PM' && hour !== 12) {
            hour += 12;
        } else if (period && period.toUpperCase() === 'AM' && hour === 12) {
            hour = 0;
        }

        const bookingDateInput = document.getElementById('bookingDateInput');
        let _year, _monthIndex, _day;
        if (bookingDateInput && bookingDateInput.value) {
            const parts = bookingDateInput.value.split('-').map(Number);
            if (parts.length === 3) {
                _year = parts[0];
                _monthIndex = parts[1] - 1;
                _day = parts[2];
            }
        }
        if (!_year) {
            const now = new Date();
            _year = now.getFullYear();
            _monthIndex = now.getMonth();
            _day = now.getDate();
        }

        const tz = document.getElementById('bookingTimezoneInput')?.value
            || document.getElementById('selectedTimezone')?.value
            || getDetectedTimeZone();

        const utcForSlot = getUtcTimestampForSelection(_day, _monthIndex, _year, hour, parseInt(minutes, 10), tz);

        if (utcForSlot.getTime() <= Date.now()) {
            if (bookingFormErrorEl) {
                bookingFormErrorEl.innerHTML = 'Please Select a Valid Time';
                bookingFormErrorEl.hidden = false;
            }
            document.querySelectorAll('.time-slot').forEach((item) => item.classList.remove('selected'));
            const bookingTimeInputInvalid = document.getElementById('bookingTimeInput');
            if (bookingTimeInputInvalid) {
                bookingTimeInputInvalid.value = '';
            }
            const bookingUtcInputInvalid = document.getElementById('bookingUtcInput');
            if (bookingUtcInputInvalid) {
                bookingUtcInputInvalid.value = '';
            }
            return;
        }

        if (bookingFormErrorEl) {
            bookingFormErrorEl.hidden = true;
            bookingFormErrorEl.textContent = '';
        }

        document.querySelectorAll('.time-slot').forEach((item) => item.classList.remove('selected'));
        slot.classList.add('selected');

        const bookingTimeInput = document.getElementById('bookingTimeInput');
        if (bookingTimeInput) {
            bookingTimeInput.value = String(hour).padStart(2, '0') + ':' + minutes;
        }

        const bookingUtcInput = document.getElementById('bookingUtcInput');
        if (bookingUtcInput) bookingUtcInput.value = utcForSlot.toISOString();

        const bookingLocalInput = document.getElementById('bookingLocalInput');
        if (bookingLocalInput) {
            bookingLocalInput.value = `${_year}-${String(_monthIndex + 1).padStart(2, '0')}-${String(_day).padStart(2, '0')} ${String(hour).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
        }

        const selectedTimezoneInput = document.getElementById('selectedTimezone');
        if (selectedTimezoneInput) selectedTimezoneInput.value = tz;
        const bookingTimezoneInput = document.getElementById('bookingTimezoneInput');
        if (bookingTimezoneInput) bookingTimezoneInput.value = tz;

        const display = document.getElementById('bookingDateDisplay');
        if (display) {
            const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const dayName = dayNames[new Date(_year, _monthIndex, _day).getDay()];
            display.innerHTML = `
                    <h5 class="booking-day-name">${dayName}</h5>
                    <p class="booking-day-num">${_day}</p>
                `;
        }
    };

    attachTimeSlotHandlers();

    const initialTimezone = document.getElementById('bookingTimezoneInput')?.value
        || document.getElementById('selectedTimezone')?.value
        || getDetectedTimeZone();
    setSelectedTimeZone(initialTimezone, null);
    renderTimezoneOptions('');

    const bookingDateInput = document.getElementById('bookingDateInput');
    if (bookingDateInput && bookingDateInput.value) {
        fetchBookedTimes(bookingDateInput.value, initialTimezone);
    }
    restoreSelectedTimeSlot();
}
