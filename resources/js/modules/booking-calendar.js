export function initBookingCalendar() {
    const bookingCalendar = document.getElementById('bookingCalendar');
    if (!bookingCalendar || bookingCalendar.dataset.calendarBound === '1') return;
    bookingCalendar.dataset.calendarBound = '1';

    const closeBtn = document.querySelector('.booking-calendar-close');
    const bookingToggles = document.querySelectorAll('[data-request-type="book_call"]');
    const timezoneInput = document.getElementById('timezoneSearch');
    const timezoneDropdown = document.getElementById('timezoneDropdown');
    const timezoneSelector = document.getElementById('timezoneSelector');
    const timezoneSelectedLabel = document.getElementById('timezoneSelectedLabel');
    const selectedTimezoneInput = document.getElementById('selectedTimezone');
    const selectedBookingUtcInput = document.getElementById('selectedBookingUtc');
    const selectedBookingLocalInput = document.getElementById('selectedBookingLocal');
    const bookingTimes = document.getElementById('bookingTimes');
    const bookingSlots = document.querySelector('.booking-slots');
    const bookingUnavailableMessage = document.getElementById('bookingUnavailableMessage');
    const bookingFormFooter = document.querySelector('.booking-form-footer');
    const bookingForm = document.querySelector('.booking-form');
    const calendarDates = document.getElementById('calendarDates');
    const calendarMonthLabel = document.getElementById('calendarMonth');
    const bookingUtcInput = document.getElementById('bookingUtcInput');
    const bookingLocalInput = document.getElementById('bookingLocalInput');
    const bookingTimezoneInput = document.getElementById('bookingTimezoneInput');
    const bookingDateInput = document.getElementById('bookingDateInput');
    const bookingTimeInput = document.getElementById('bookingTimeInput');
    const serviceInput = document.getElementById('serviceInput');
    let selectedTimeZone = 'Asia/Manila';
    let selectedDay = null;
    let selectedMonth = null;
    let selectedYear = null;
    let selectedHour = null;
    let selectedMinute = null;

    const supportedTimeZones = (() => {
        if (typeof Intl !== 'undefined' && typeof Intl.supportedValuesOf === 'function') {
            try {
                return Intl.supportedValuesOf('timeZone').sort();
            } catch (error) {
                // fall through
            }
        }

        return [
            'UTC', 'GMT', 'Etc/UTC', 'Africa/Abidjan', 'Africa/Accra', 'Africa/Addis_Ababa', 'Africa/Algiers', 'Africa/Asmara', 'Africa/Bamako', 'Africa/Bangui', 'Africa/Banjul', 'Africa/Bissau', 'Africa/Blantyre', 'Africa/Brazzaville', 'Africa/Bujumbura', 'Africa/Cairo', 'Africa/Casablanca', 'Africa/Ceuta', 'Africa/Conakry', 'Africa/Dakar', 'Africa/Dar_es_Salaam', 'Africa/Djibouti', 'Africa/Douala', 'Africa/El_Aaiun', 'Africa/Freetown', 'Africa/Gaborone', 'Africa/Harare', 'Africa/Johannesburg', 'Africa/Juba', 'Africa/Kampala', 'Africa/Khartoum', 'Africa/Kigali', 'Africa/Kinshasa', 'Africa/Lagos', 'Africa/Libreville', 'Africa/Lome', 'Africa/Luanda', 'Africa/Lubumbashi', 'Africa/Lusaka', 'Africa/Malabo', 'Africa/Maputo', 'Africa/Maseru', 'Africa/Mbabane', 'Africa/Mogadishu', 'Africa/Monrovia', 'Africa/Nairobi', 'Africa/Ndjamena', 'Africa/Niamey', 'Africa/Nouakchott', 'Africa/Ouagadougou', 'Africa/Porto-Novo', 'Africa/Sao_Tome', 'Africa/Tripoli', 'Africa/Tunis', 'Africa/Windhoek', 'America/Adak', 'America/Anchorage', 'America/Anguilla', 'America/Antigua', 'America/Araguaina', 'America/Argentina/Buenos_Aires', 'America/Aruba', 'America/Asuncion', 'America/Atikokan', 'America/Bahia', 'America/Bahia_Banderas', 'America/Barbados', 'America/Belem', 'America/Belize', 'America/Blanc-Sablon', 'America/Boa_Vista', 'America/Bogota', 'America/Boise', 'America/Cambridge_Bay', 'America/Campo_Grande', 'America/Cancun', 'America/Caracas', 'America/Cayenne', 'America/Cayman', 'America/Chicago', 'America/Chihuahua', 'America/Ciudad_Juarez', 'America/Costa_Rica', 'America/Creston', 'America/Cuiaba', 'America/Curacao', 'America/Danmarkshavn', 'America/Dawson', 'America/Dawson_Creek', 'America/Denver', 'America/Detroit', 'America/Dominica', 'America/Edmonton', 'America/Eirunepe', 'America/El_Salvador', 'America/Fortaleza', 'America/Glace_Bay', 'America/Goose_Bay', 'America/Grand_Turk', 'America/Grenada', 'America/Guadeloupe', 'America/Guatemala', 'America/Guayaquil', 'America/Guyana', 'America/Halifax', 'America/Havana', 'America/Hermosillo', 'America/Indiana/Indianapolis', 'America/Indiana/Knox', 'America/Indiana/Marengo', 'America/Indiana/Petersburg', 'America/Indiana/Tell_City', 'America/Indiana/Vevay', 'America/Indiana/Vincennes', 'America/Indiana/Winamac', 'America/Inuvik', 'America/Iqaluit', 'America/Jamaica', 'America/Juneau', 'America/Kentucky/Louisville', 'America/Kentucky/Monticello', 'America/Kralendijk', 'America/La_Paz', 'America/Lima', 'America/Los_Angeles', 'America/Lower_Princes', 'America/Maceio', 'America/Managua', 'America/Manaus', 'America/Marigot', 'America/Martinique', 'America/Matamoros', 'America/Mazatlan', 'America/Menominee', 'America/Merida', 'America/Metlakatla', 'America/Mexico_City', 'America/Miquelon', 'America/Moncton', 'America/Monterrey', 'America/Montevideo', 'America/Montserrat', 'America/Nassau', 'America/New_York', 'America/Nipigon', 'America/Nome', 'America/Noronha', 'America/North_Dakota/Beulah', 'America/North_Dakota/Center', 'America/North_Dakota/New_Salem', 'America/Nuuk', 'America/Ojinaga', 'America/Panama', 'America/Pangnirtung', 'America/Paramaribo', 'America/Phoenix', 'America/Port-au-Prince', 'America/Port_of_Spain', 'America/Porto_Velho', 'America/Puerto_Rico', 'America/Punta_Arenas', 'America/Rainy_River', 'America/Rankin_Inlet', 'America/Recife', 'America/Regina', 'America/Resolute', 'America/Rio_Branco', 'America/Santarem', 'America/Santiago', 'America/Santo_Domingo', 'America/Sao_Paulo', 'America/Scoresbysund', 'America/Sitka', 'America/St_Barthelemy', 'America/St_Johns', 'America/St_Kitts', 'America/St_Lucia', 'America/St_Thomas', 'America/St_Vincent', 'America/Swift_Current', 'America/Tegucigalpa', 'America/Thule', 'America/Thunder_Bay', 'America/Tijuana', 'America/Toronto', 'America/Tortola', 'America/Vancouver', 'America/Whitehorse', 'America/Winnipeg', 'America/Yakutat', 'America/Yellowknife', 'Antarctica/Casey', 'Antarctica/Davis', 'Antarctica/DumontDUrville', 'Antarctica/Macquarie', 'Antarctica/Mawson', 'Antarctica/Palmer', 'Antarctica/Rothera', 'Antarctica/Syowa', 'Antarctica/Troll', 'Antarctica/Vostok', 'Arctic/Longyearbyen', 'Asia/Aden', 'Asia/Almaty', 'Asia/Amman', 'Asia/Anadyr', 'Asia/Aqtau', 'Asia/Aqtobe', 'Asia/Ashgabat', 'Asia/Atyrau', 'Asia/Baghdad', 'Asia/Bahrain', 'Asia/Baku', 'Asia/Bangkok', 'Asia/Barnaul', 'Asia/Beirut', 'Asia/Bishkek', 'Asia/Brunei', 'Asia/Chita', 'Asia/Choibalsan', 'Asia/Colombo', 'Asia/Damascus', 'Asia/Dhaka', 'Asia/Dili', 'Asia/Dubai', 'Asia/Dushanbe', 'Asia/Famagusta', 'Asia/Gaza', 'Asia/Hebron', 'Asia/Ho_Chi_Minh', 'Asia/Hong_Kong', 'Asia/Hovd', 'Asia/Irkutsk', 'Asia/Jakarta', 'Asia/Jayapura', 'Asia/Jerusalem', 'Asia/Kabul', 'Asia/Kamchatka', 'Asia/Karachi', 'Asia/Kathmandu', 'Asia/Khandyga', 'Asia/Kolkata', 'Asia/Krasnoyarsk', 'Asia/Kuala_Lumpur', 'Asia/Kuching', 'Asia/Kuwait', 'Asia/Macau', 'Asia/Magadan', 'Asia/Makassar', 'Asia/Manila', 'Asia/Muscat', 'Asia/Nicosia', 'Asia/Novokuznetsk', 'Asia/Novosibirsk', 'Asia/Omsk', 'Asia/Oral', 'Asia/Phnom_Penh', 'Asia/Pontianak', 'Asia/Pyongyang', 'Asia/Qatar', 'Asia/Qostanay', 'Asia/Qyzylorda', 'Asia/Riyadh', 'Asia/Sakhalin', 'Asia/Samarkand', 'Asia/Seoul', 'Asia/Shanghai', 'Asia/Singapore', 'Asia/Srednekolymsk', 'Asia/Taipei', 'Asia/Tashkent', 'Asia/Tbilisi', 'Asia/Tehran', 'Asia/Thimphu', 'Asia/Tokyo', 'Asia/Tomsk', 'Asia/Ulaanbaatar', 'Asia/Urumqi', 'Asia/Ust-Nera', 'Asia/Vientiane', 'Asia/Vladivostok', 'Asia/Yakutsk', 'Asia/Yangon', 'Asia/Yekaterinburg', 'Asia/Yerevan', 'Atlantic/Azores', 'Atlantic/Bermuda', 'Atlantic/Canary', 'Atlantic/Cape_Verde', 'Atlantic/Faroe', 'Atlantic/Madeira', 'Atlantic/Reykjavik', 'Atlantic/South_Georgia', 'Atlantic/St_Helena', 'Atlantic/Stanley', 'Australia/Adelaide', 'Australia/Brisbane', 'Australia/Broken_Hill', 'Australia/Darwin', 'Australia/Eucla', 'Australia/Hobart', 'Australia/Lindeman', 'Australia/Lord_Howe', 'Australia/Melbourne', 'Australia/Perth', 'Australia/Sydney', 'Europe/Amsterdam', 'Europe/Andorra', 'Europe/Astrakhan', 'Europe/Athens', 'Europe/Belgrade', 'Europe/Berlin', 'Europe/Bratislava', 'Europe/Brussels', 'Europe/Bucharest', 'Europe/Budapest', 'Europe/Busingen', 'Europe/Chisinau', 'Europe/Copenhagen', 'Europe/Dublin', 'Europe/Gibraltar', 'Europe/Helsinki', 'Europe/Isle_of_Man', 'Europe/Istanbul', 'Europe/Jersey', 'Europe/Kaliningrad', 'Europe/Kiev', 'Europe/Kirov', 'Europe/Lisbon', 'Europe/Ljubljana', 'Europe/London', 'Europe/Luxembourg', 'Europe/Madrid', 'Europe/Malta', 'Europe/Mariehamn', 'Europe/Minsk', 'Europe/Monaco', 'Europe/Moscow', 'Europe/Oslo', 'Europe/Paris', 'Europe/Podgorica', 'Europe/Prague', 'Europe/Riga', 'Europe/Rome', 'Europe/Samara', 'Europe/San_Marino', 'Europe/Sarajevo', 'Europe/Saratov', 'Europe/Simferopol', 'Europe/Skopje', 'Europe/Sofia', 'Europe/Stockholm', 'Europe/Tallinn', 'Europe/Tirane', 'Europe/Ulyanovsk', 'Europe/Uzhgorod', 'Europe/Vaduz', 'Europe/Vatican', 'Europe/Vienna', 'Europe/Vilnius', 'Europe/Volgograd', 'Europe/Warsaw', 'Europe/Zagreb', 'Europe/Zaporozhye', 'Europe/Zurich', 'Indian/Antananarivo', 'Indian/Chagos', 'Indian/Christmas', 'Indian/Cocos', 'Indian/Comoro', 'Indian/Kerguelen', 'Indian/Mahe', 'Indian/Maldives', 'Indian/Mauritius', 'Indian/Mayotte', 'Indian/Reunion', 'Pacific/Apia', 'Pacific/Auckland', 'Pacific/Bougainville', 'Pacific/Chatham', 'Pacific/Chuuk', 'Pacific/Easter', 'Pacific/Efate', 'Pacific/Fakaofo', 'Pacific/Fiji', 'Pacific/Funafuti', 'Pacific/Galapagos', 'Pacific/Gambier', 'Pacific/Guadalcanal', 'Pacific/Guam', 'Pacific/Honolulu', 'Pacific/Kiritimati', 'Pacific/Kosrae', 'Pacific/Kwajalein', 'Pacific/Majuro', 'Pacific/Marquesas', 'Pacific/Midway', 'Pacific/Nauru', 'Pacific/Niue', 'Pacific/Norfolk', 'Pacific/Noumea', 'Pacific/Pago_Pago', 'Pacific/Palau', 'Pacific/Pitcairn', 'Pacific/Pohnpei', 'Pacific/Port_Moresby', 'Pacific/Rarotonga', 'Pacific/Saipan', 'Pacific/Tahiti', 'Pacific/Tarawa', 'Pacific/Tongatapu', 'Pacific/Wake', 'Pacific/Wallis', 'Pacific/Apia', 'UTC'
        ];
    })();

    function getTimeZoneAbbreviation(timeZone, date) {
        const formatter = new Intl.DateTimeFormat('en-US', {
            timeZone,
            timeZoneName: 'short',
        });

        const parts = formatter.formatToParts(date);
        const zoneNamePart = parts.find((part) => part.type === 'timeZoneName');
        return zoneNamePart ? zoneNamePart.value : 'UTC';
    }

    function getTimeZoneOffsetMinutes(timeZone, date) {
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
    }

    function formatUtcOffsetLabel(timeZone, date) {
        const offsetMinutes = getTimeZoneOffsetMinutes(timeZone, date);
        const sign = offsetMinutes >= 0 ? '+' : '-';
        const absMinutes = Math.abs(offsetMinutes);
        const hours = Math.floor(absMinutes / 60);
        const minutes = absMinutes % 60;
        return `UTC${sign}${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
    }

    function getUtcTimestampForSelection(day, monthIndex, yearNumber, hour, minute, timeZone = selectedTimeZone) {
        const baseDate = new Date(Date.UTC(yearNumber, monthIndex, day, hour, minute));
        const offsetMinutes = getTimeZoneOffsetMinutes(timeZone, baseDate);
        return new Date(baseDate.getTime() - (offsetMinutes * 60 * 1000));
    }

    function formatTimeInZone(utcDate) {
        return new Intl.DateTimeFormat('en-US', {
            timeZone: selectedTimeZone,
            hour: 'numeric',
            minute: '2-digit',
            hour12: true,
        }).format(utcDate);
    }

    function formatDateTitle(date) {
        const dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        return `${dayNames[date.getDay()]}, ${monthNames[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
    }

    function formatDateInputValue(yearNumber, monthIndex, day) {
        return `${yearNumber}-${String(monthIndex + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    }

    function isSelectedDateSunday() {
        if (selectedDay === null || selectedMonth === null || selectedYear === null) {
            return false;
        }
        const day = new Date(selectedYear, selectedMonth, selectedDay).getDay();
        return day === 0;
    }

    function updateBookingSelection() {
        const display = document.getElementById('bookingDateDisplay');
        if (!display) return;

        const sundaySelected = isSelectedDateSunday();

        if (sundaySelected) {
            if (bookingUnavailableMessage) {
                bookingUnavailableMessage.hidden = false;
            }
            if (bookingSlots) {
                bookingSlots.hidden = true;
            }
            if (bookingFormFooter) {
                bookingFormFooter.hidden = true;
            }
            if (bookingForm) {
                bookingForm.hidden = true;
            }
            if (bookingUtcInput) {
                bookingUtcInput.value = '';
            }
            if (bookingLocalInput) {
                bookingLocalInput.value = '';
            }
            if (bookingDateInput) {
                bookingDateInput.value = '';
            }
            if (bookingTimeInput) {
                bookingTimeInput.value = '';
            }
            if (bookingTimes) {
                bookingTimes.innerHTML = '';
            }
            display.innerHTML = `
                <h5 class="booking-day-name">Sundays are unavailable for booking.</h5>
                <p class="booking-day-num"></p>
            `;
            return;
        }

        if (bookingUnavailableMessage) {
            bookingUnavailableMessage.hidden = true;
        }
        if (bookingSlots) {
            bookingSlots.hidden = false;
        }
        if (bookingFormFooter) {
            bookingFormFooter.hidden = false;
        }
        if (bookingForm) {
            bookingForm.hidden = false;
        }

        if (!selectedDay || !selectedMonth || !selectedYear || selectedHour === null || selectedMinute === null) {
            display.innerHTML = `
                <h5 class="booking-day-name">Select a date & time</h5>
                <p class="booking-day-num"></p>
            `;
            return;
        }

        const utcDate = getUtcTimestampForSelection(selectedDay, selectedMonth, selectedYear, selectedHour, selectedMinute);
        const selectedDate = new Date(Date.UTC(selectedYear, selectedMonth, selectedDay));
        const abbreviation = getTimeZoneAbbreviation(selectedTimeZone, utcDate);

        display.innerHTML = `
            <h5 class="booking-day-name">${formatDateTitle(selectedDate)}</h5>
            <p class="booking-day-num">${selectedDay}</p>
            <p class="booking-selected-time">${formatTimeInZone(utcDate)} <span class="booking-timezone-abbr">${abbreviation}</span></p>
            <p class="booking-selected-utc">UTC: ${utcDate.toISOString()}</p>
        `;

        if (selectedBookingUtcInput) {
            selectedBookingUtcInput.value = utcDate.toISOString();
        }

        if (bookingUtcInput) {
            bookingUtcInput.value = utcDate.toISOString();
        }

        if (selectedBookingLocalInput) {
            const localDateString = `${selectedYear}-${String(selectedMonth + 1).padStart(2, '0')}-${String(selectedDay).padStart(2, '0')} ${String(selectedHour).padStart(2, '0')}:${String(selectedMinute).padStart(2, '0')}`;
            selectedBookingLocalInput.value = localDateString;
        }

        if (bookingLocalInput) {
            bookingLocalInput.value = `${selectedYear}-${String(selectedMonth + 1).padStart(2, '0')}-${String(selectedDay).padStart(2, '0')} ${String(selectedHour).padStart(2, '0')}:${String(selectedMinute).padStart(2, '0')}`;
        }

        if (selectedTimezoneInput) {
            selectedTimezoneInput.value = selectedTimeZone;
        }

        if (bookingTimezoneInput) {
            bookingTimezoneInput.value = selectedTimeZone;
        }

        if (timezoneSelectedLabel) {
            timezoneSelectedLabel.textContent = selectedTimeZone;
        }

        if (bookingDateInput) {
            bookingDateInput.value = formatDateInputValue(selectedYear, selectedMonth, selectedDay);
        }

        if (bookingTimeInput) {
            if (selectedHour !== null && selectedMinute !== null) {
                bookingTimeInput.value = `${String(selectedHour).padStart(2, '0')}:${String(selectedMinute).padStart(2, '0')}`;
            } else {
                bookingTimeInput.value = '';
            }
        }

        if (serviceInput) {
            serviceInput.value = serviceInput.value || 'Discovery Call';
        }
    }

    function showBookingFormError(message) {
        const errorEl = document.getElementById('bookingFormError');
        if (!errorEl) {
            alert(message);
            return;
        }

        errorEl.textContent = message;
        errorEl.hidden = false;
        errorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function clearBookingFormError() {
        const errorEl = document.getElementById('bookingFormError');
        if (errorEl) {
            errorEl.hidden = true;
            errorEl.textContent = '';
        }
    }

    function parseBookingDateTimeFromInputs() {
        if (!bookingDateInput || !bookingTimeInput) {
            return null;
        }

        const dateValue = bookingDateInput.value;
        const timeValue = bookingTimeInput.value;
        if (!dateValue || !timeValue) {
            return null;
        }

        const dateParts = dateValue.split('-').map(Number);
        const timeParts = timeValue.split(':').map(Number);
        if (dateParts.length !== 3 || timeParts.length !== 2 || dateParts.some(Number.isNaN) || timeParts.some(Number.isNaN)) {
            return null;
        }

        return {
            year: dateParts[0],
            month: dateParts[1] - 1,
            day: dateParts[2],
            hour: timeParts[0],
            minute: timeParts[1],
            timezone: bookingTimezoneInput?.value?.trim() || selectedTimeZone,
        };
    }

    function restoreBookingSelectionFromInputs() {
        const parsed = parseBookingDateTimeFromInputs();
        if (!parsed) {
            return;
        }

        selectedTimeZone = parsed.timezone || selectedTimeZone;
        if (timezoneInput) {
            timezoneInput.value = selectedTimeZone;
        }
        if (selectedTimezoneInput) {
            selectedTimezoneInput.value = selectedTimeZone;
        }
        if (bookingTimezoneInput) {
            bookingTimezoneInput.value = selectedTimeZone;
        }
        if (timezoneSelectedLabel) {
            timezoneSelectedLabel.textContent = selectedTimeZone;
        }

        selectedYear = parsed.year;
        selectedMonth = parsed.month;
        selectedDay = parsed.day;
        selectedHour = parsed.hour;
        selectedMinute = parsed.minute;
        calendarViewMonth = parsed.month;
        calendarViewYear = parsed.year;
    }

    if (bookingForm) {
        bookingForm.addEventListener('submit', (event) => {
            clearBookingFormError();
            const dateValue = bookingDateInput?.value;
            const timeValue = bookingTimeInput?.value;
            const timezoneValue = bookingTimezoneInput?.value || selectedTimeZone;

            if (!dateValue || !timeValue) {
                event.preventDefault();
                showBookingFormError('Please select a booking date and time before submitting.');
                return;
            }

            const dateParts = dateValue.split('-').map(Number);
            const timeParts = timeValue.split(':').map(Number);
            if (dateParts.length !== 3 || timeParts.length !== 2 || dateParts.some(Number.isNaN) || timeParts.some(Number.isNaN)) {
                event.preventDefault();
                showBookingFormError('Please select a valid booking date and time.');
                return;
            }

            const selectedUtc = getUtcTimestampForSelection(dateParts[2], dateParts[1] - 1, dateParts[0], timeParts[0], timeParts[1], timezoneValue);
            if (selectedUtc.getTime() < Date.now()) {
                event.preventDefault();
                showBookingFormError('Please select a future booking date and time.');
            }
        });
    }

    async function renderTimeSlots() {
        if (!bookingTimes) return;

        bookingTimes.innerHTML = '';
        if (!selectedDay || selectedMonth === null || selectedYear === null) {
            selectedHour = null;
            selectedMinute = null;
            updateBookingSelection();
            return;
        }

        const selectedDate = new Date(selectedYear, selectedMonth, selectedDay);
        if (selectedDate.getDay() === 0) {
            if (bookingTimes) {
                bookingTimes.innerHTML = '';
            }
            updateBookingSelection();
            return;
        }

        const dateValue = formatDateInputValue(selectedYear, selectedMonth, selectedDay);
        const service = serviceInput ? serviceInput.value : 'Discovery Call';

        try {
            const response = await fetch(`/bookings/availability?booking_date=${encodeURIComponent(dateValue)}&booking_timezone=${encodeURIComponent(selectedTimeZone)}&service=${encodeURIComponent(service)}`);
            const payload = await response.json();
            const slotValues = [
                { hour: 8, minute: 0 }, { hour: 8, minute: 30 }, { hour: 9, minute: 0 }, { hour: 9, minute: 30 },
                { hour: 10, minute: 0 }, { hour: 10, minute: 30 }, { hour: 11, minute: 0 }, { hour: 11, minute: 30 },
                { hour: 12, minute: 0 }, { hour: 12, minute: 30 }, { hour: 13, minute: 0 }, { hour: 13, minute: 30 },
                { hour: 14, minute: 0 }, { hour: 14, minute: 30 }, { hour: 15, minute: 0 }, { hour: 15, minute: 30 },
                { hour: 16, minute: 0 }, { hour: 16, minute: 30 }, { hour: 17, minute: 0 }, { hour: 17, minute: 30 }
            ];

            const availableTimes = new Set(payload.available || []);

            slotValues.forEach((slot) => {
                const slotLabel = `${String(slot.hour).padStart(2, '0')}:${String(slot.minute).padStart(2, '0')}`;
                const isAvailable = availableTimes.has(slotLabel);
                const slotEl = document.createElement('button');
                slotEl.type = 'button';
                slotEl.className = 'time-slot';
                slotEl.dataset.hour = String(slot.hour);
                slotEl.dataset.minute = String(slot.minute);
                if (!isAvailable) {
                    slotEl.classList.add('disabled');
                    slotEl.disabled = true;
                }
                const utcDate = getUtcTimestampForSelection(selectedDay || 1, selectedMonth || new Date().getMonth(), selectedYear || new Date().getFullYear(), slot.hour, slot.minute);
                slotEl.textContent = formatTimeInZone(utcDate);
                slotEl.addEventListener('click', () => {
                    if (slotEl.disabled) return;
                    document.querySelectorAll('.time-slot').forEach((item) => item.classList.remove('selected'));
                    slotEl.classList.add('selected');
                    selectedHour = slot.hour;
                    selectedMinute = slot.minute;
                    updateBookingSelection();
                });
                bookingTimes.appendChild(slotEl);
            });
        } catch (error) {
            bookingTimes.innerHTML = '<div class="time-slot disabled">Availability unavailable</div>';
        }
    }

    const commonTimeZones = [
        { label: 'Pacific Time (PT)', timeZone: 'America/Los_Angeles', offset: 'UTC−08:00 / UTC−07:00', description: 'USA (Los Angeles, Seattle)' },
        { label: 'Mountain Time (MT)', timeZone: 'America/Denver', offset: 'UTC−07:00 / UTC−06:00', description: 'USA (Denver)' },
        { label: 'Central Time (CT)', timeZone: 'America/Chicago', offset: 'UTC−06:00 / UTC−05:00', description: 'USA (Chicago, Dallas)' },
        { label: 'Eastern Time (ET)', timeZone: 'America/New_York', offset: 'UTC−05:00 / UTC−04:00', description: 'USA (New York, Miami)' },
        { label: 'Alaska Time (AKT)', timeZone: 'America/Anchorage', offset: 'UTC−09:00 / UTC−08:00', description: 'Alaska' },
        { label: 'Hawaii Time (HST)', timeZone: 'Pacific/Honolulu', offset: 'UTC−10:00', description: 'Hawaii' },
        { label: 'Atlantic Time (AT)', timeZone: 'America/Halifax', offset: 'UTC−04:00 / UTC−03:00', description: 'Canada (Halifax)' },
        { label: 'Newfoundland Time (NT)', timeZone: 'America/St_Johns', offset: 'UTC−03:30 / UTC−02:30', description: 'Newfoundland, Canada' },
        { label: 'Greenwich Mean Time (GMT)', timeZone: 'Europe/London', offset: 'UTC+00:00', description: 'UK, Portugal' },
        { label: 'Central European Time (CET)', timeZone: 'Europe/Paris', offset: 'UTC+01:00', description: 'Germany, France, Italy' },
        { label: 'Eastern European Time (EET)', timeZone: 'Europe/Athens', offset: 'UTC+02:00', description: 'Greece, Finland' },
        { label: 'Moscow Time (MSK)', timeZone: 'Europe/Moscow', offset: 'UTC+03:00', description: 'Russia (Moscow)' },
        { label: 'Gulf Standard Time (GST)', timeZone: 'Asia/Dubai', offset: 'UTC+04:00', description: 'UAE, Dubai' },
        { label: 'Pakistan Standard Time (PKT)', timeZone: 'Asia/Karachi', offset: 'UTC+05:00', description: 'Pakistan' },
        { label: 'India Standard Time (IST)', timeZone: 'Asia/Kolkata', offset: 'UTC+05:30', description: 'India' },
        { label: 'Bangladesh Standard Time (BST)', timeZone: 'Asia/Dhaka', offset: 'UTC+06:00', description: 'Bangladesh' },
        { label: 'China Standard Time (CST)', timeZone: 'Asia/Shanghai', offset: 'UTC+08:00', description: 'China' },
        { label: 'Philippine Standard Time (PHT)', timeZone: 'Asia/Manila', offset: 'UTC+08:00', description: 'Philippines' },
        { label: 'Singapore Standard Time (SGT)', timeZone: 'Asia/Singapore', offset: 'UTC+08:00', description: 'Singapore' },
        { label: 'Japan Standard Time (JST)', timeZone: 'Asia/Tokyo', offset: 'UTC+09:00', description: 'Japan' },
        { label: 'Korea Standard Time (KST)', timeZone: 'Asia/Seoul', offset: 'UTC+09:00', description: 'South Korea' },
        { label: 'Australian Central Time (ACST)', timeZone: 'Australia/Adelaide', offset: 'UTC+09:30', description: 'Australia (Adelaide)' },
        { label: 'Australian Eastern Time (AET)', timeZone: 'Australia/Sydney', offset: 'UTC+10:00', description: 'Australia (Sydney, Melbourne)' },
        { label: 'New Zealand Time (NZST)', timeZone: 'Pacific/Auckland', offset: 'UTC+12:00', description: 'New Zealand' }
    ];

    function buildTimeZoneOptions(filter = '') {
        if (!timezoneDropdown) return;
        const normalizedFilter = filter.trim().toLowerCase();
        const filteredZones = supportedTimeZones.filter((timeZone) => {
            if (!normalizedFilter) return true;
            return timeZone.toLowerCase().includes(normalizedFilter);
        });

        timezoneDropdown.innerHTML = '';
        const renderedZones = new Set();

        const tableHeader = document.createElement('div');
        tableHeader.className = 'timezone-table-header';
        tableHeader.innerHTML = '<span>Time Zone</span><span>UTC Offset</span><span>Common Locations</span>';
        timezoneDropdown.appendChild(tableHeader);

        function appendTimeZoneOption(timeZone, label = null, offset = null, description = null) {
            if (renderedZones.has(timeZone)) return;
            renderedZones.add(timeZone);

            const option = document.createElement('button');
            option.type = 'button';
            option.className = 'timezone-option';
            option.dataset.timezone = timeZone;
            option.dataset.label = label || timeZone;
            const title = label || timeZone;
            const subtitle = description || '';
            const displayOffset = typeof offset === 'string'
                ? offset
                : formatUtcOffsetLabel(timeZone, new Date());
            option.innerHTML = `
                <span class="timezone-option-name">${title}</span>
                <span class="timezone-option-offset">${displayOffset}</span>
                <span class="timezone-option-location">${subtitle}</span>
            `;
            option.addEventListener('mousedown', (event) => {
                event.preventDefault();
                selectedTimeZone = timeZone;
                if (timezoneInput) {
                    // Show the friendly label (e.g. "Pacific Time (PT)") in the search box
                    // instead of the raw IANA id (e.g. "America/Los_Angeles").
                    timezoneInput.value = title;
                }
                if (selectedTimezoneInput) {
                    // Keep the raw IANA zone in the hidden field — this is what
                    // the offset/date math and the server rely on.
                    selectedTimezoneInput.value = timeZone;
                }
                if (bookingTimezoneInput) {
                    bookingTimezoneInput.value = timeZone;
                }
                if (timezoneSelectedLabel) {
                    timezoneSelectedLabel.textContent = title;
                }
                renderTimeSlots();
                updateBookingSelection();
                closeTimeZoneDropdown();
            });
            timezoneDropdown.appendChild(option);
        }

        const commonMatches = commonTimeZones.filter((entry) => {
            if (!normalizedFilter) return true;
            return entry.label.toLowerCase().includes(normalizedFilter) || entry.timeZone.toLowerCase().includes(normalizedFilter) || entry.description.toLowerCase().includes(normalizedFilter);
        });

        if (commonMatches.length) {
            const commonHeader = document.createElement('div');
            commonHeader.className = 'timezone-group-label';
            commonHeader.textContent = 'Common time zones';
            timezoneDropdown.appendChild(commonHeader);
            commonMatches.forEach((entry) => appendTimeZoneOption(entry.timeZone, entry.label, entry.offset, entry.description));
        }

        if (!filteredZones.length) {
            if (!commonMatches.length) {
                const emptyOption = document.createElement('div');
                emptyOption.className = 'timezone-option';
                emptyOption.innerHTML = '<strong>No matching time zones</strong><small>Try a different search term</small>';
                timezoneDropdown.appendChild(emptyOption);
            }
            return;
        }

        const fullListHeader = document.createElement('div');
        fullListHeader.className = 'timezone-group-label';
        fullListHeader.textContent = normalizedFilter ? 'Matching time zones' : 'All time zones';
        timezoneDropdown.appendChild(fullListHeader);

        filteredZones.forEach((timeZone) => appendTimeZoneOption(timeZone));
    }

    function openTimeZoneDropdown() {
        if (!timezoneDropdown || !timezoneSelector) return;
        timezoneDropdown.hidden = false;
        timezoneSelector.setAttribute('aria-expanded', 'true');

        let filter = '';
        if (timezoneInput && timezoneInput.value.trim() && timezoneInput.value.trim() !== selectedTimeZone) {
            filter = timezoneInput.value.trim();
        }

        buildTimeZoneOptions(filter);
    }

    if (timezoneDropdown) {
        timezoneDropdown.addEventListener('click', (event) => {
            const option = event.target.closest('.timezone-option');
            if (!option) return;
            const timeZone = option.dataset.timezone;
            const label = option.dataset.label || option.querySelector('strong')?.textContent || timeZone;

            if (timeZone) {
                selectedTimeZone = timeZone;
                if (timezoneInput) {
                    // Show the friendly label instead of the raw IANA id here too,
                    // so both selection paths (mousedown handler above and this
                    // delegated click handler) stay in sync.
                    timezoneInput.value = label;
                }
                if (selectedTimezoneInput) {
                    selectedTimezoneInput.value = timeZone;
                }
                if (bookingTimezoneInput) {
                    bookingTimezoneInput.value = timeZone;
                }
                if (timezoneSelectedLabel) {
                    timezoneSelectedLabel.textContent = label;
                }
                renderTimeSlots();
                updateBookingSelection();
                closeTimeZoneDropdown();
            }
        });
    }

    function closeTimeZoneDropdown() {
        if (!timezoneDropdown || !timezoneSelector) return;
        timezoneDropdown.hidden = true;
        timezoneSelector.setAttribute('aria-expanded', 'false');
    }

    function initializeTimeZonePicker() {
        const today = new Date();

        restoreBookingSelectionFromInputs();

        if (!selectedTimeZone) {
            const detectedZone = typeof Intl !== 'undefined' && Intl.DateTimeFormat && Intl.DateTimeFormat().resolvedOptions
                ? Intl.DateTimeFormat().resolvedOptions().timeZone
                : 'UTC';

            selectedTimeZone = supportedTimeZones.includes(detectedZone) ? detectedZone : 'Asia/Manila';
        }

        if (!supportedTimeZones.includes(selectedTimeZone)) {
            selectedTimeZone = 'Asia/Manila';
        }

        if (timezoneInput) {
            timezoneInput.value = selectedTimeZone;
        }
        if (selectedTimezoneInput) {
            selectedTimezoneInput.value = selectedTimeZone;
        }
        if (bookingTimezoneInput) {
            bookingTimezoneInput.value = selectedTimeZone;
        }
        if (timezoneSelectedLabel) {
            timezoneSelectedLabel.textContent = selectedTimeZone;
        }

        if (selectedYear === null || selectedMonth === null || selectedDay === null) {
            selectedDay = today.getDate();
            selectedMonth = today.getMonth();
            selectedYear = today.getFullYear();
            selectedHour = null;
            selectedMinute = null;
            calendarViewMonth = today.getMonth();
            calendarViewYear = today.getFullYear();
        } else {
            calendarViewMonth = selectedMonth;
            calendarViewYear = selectedYear;
        }

        renderCalendar();
        renderTimeSlots();
        updateBookingSelection();
    }

    const openCalendar = () => {
        bookingCalendar.classList.add('open');
        bookingCalendar.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        initializeTimeZonePicker();
    };

    const closeCalendar = () => {
        bookingCalendar.classList.remove('open');
        bookingCalendar.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        closeTimeZoneDropdown();
    };

    bookingToggles.forEach((toggle) => {
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

    if (timezoneInput) {
        timezoneInput.addEventListener('focus', () => {
            openTimeZoneDropdown();
        });
        timezoneInput.addEventListener('input', () => {
            openTimeZoneDropdown();
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

    if (timezoneSelector) {
        timezoneSelector.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            openTimeZoneDropdown();
        });
    }

    document.addEventListener('click', (event) => {
        if (!timezoneSelector || !timezoneDropdown) return;
        const clickedInside = timezoneSelector.contains(event.target) || timezoneDropdown.contains(event.target);
        if (!clickedInside) {
            closeTimeZoneDropdown();
        }
    });

    const today = new Date();
    let calendarViewMonth = today.getMonth();
    let calendarViewYear = today.getFullYear();

    function renderCalendar() {
        if (!calendarDates) return;

        calendarDates.innerHTML = '';
        const firstDay = new Date(calendarViewYear, calendarViewMonth, 1).getDay();
        const daysInMonth = new Date(calendarViewYear, calendarViewMonth + 1, 0).getDate();
        const daysInPrevMonth = new Date(calendarViewYear, calendarViewMonth, 0).getDate();

        if (calendarMonthLabel) {
            calendarMonthLabel.textContent = new Intl.DateTimeFormat('en-US', { month: 'long', year: 'numeric' }).format(new Date(calendarViewYear, calendarViewMonth, 1));
        }

        for (let index = firstDay - 1; index >= 0; index -= 1) {
            const date = document.createElement('div');
            date.className = 'calendar-date disabled';
            date.textContent = daysInPrevMonth - index;
            calendarDates.appendChild(date);
        }

        for (let day = 1; day <= daysInMonth; day += 1) {
            const date = document.createElement('div');
            date.className = 'calendar-date';
            const currentDate = new Date(calendarViewYear, calendarViewMonth, day);
            const isPast = currentDate < new Date(today.getFullYear(), today.getMonth(), today.getDate());
            const isSunday = currentDate.getDay() === 0;
            if (day === today.getDate() && calendarViewMonth === today.getMonth() && calendarViewYear === today.getFullYear()) {
                date.classList.add('today');
            }
            if (isPast || isSunday) {
                date.classList.add('disabled');
                date.setAttribute('aria-disabled', 'true');
                date.tabIndex = -1;
                if (isPast) {
                    date.style.pointerEvents = 'none';
                }
            }
            if (isSunday) {
                date.classList.add('sunday-unavailable');
                date.setAttribute('title', 'Sundays are unavailable for booking.');
            }
            if (isSunday) {
                date.innerHTML = `<span class="calendar-day-number">${day}</span><span class="calendar-day-label">SUN</span>`;
            } else {
                date.textContent = day;
            }
            if (!date.classList.contains('disabled')) {
                date.addEventListener('click', () => {
                    document.querySelectorAll('.calendar-date').forEach((item) => item.classList.remove('selected'));
                    date.classList.add('selected');
                    selectedDay = day;
                    selectedMonth = calendarViewMonth;
                    selectedYear = calendarViewYear;
                    selectedHour = null;
                    selectedMinute = null;
                    renderTimeSlots();
                    updateBookingSelection();
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
    }

    document.querySelector('.calendar-prev')?.addEventListener('click', () => {
        calendarViewMonth -= 1;
        if (calendarViewMonth < 0) {
            calendarViewMonth = 11;
            calendarViewYear -= 1;
        }
        renderCalendar();
    });

    document.querySelector('.calendar-next')?.addEventListener('click', () => {
        calendarViewMonth += 1;
        if (calendarViewMonth > 11) {
            calendarViewMonth = 0;
            calendarViewYear += 1;
        }
        renderCalendar();
    });

    renderCalendar();
    selectedDay = today.getDate();
    selectedMonth = today.getMonth();
    selectedYear = today.getFullYear();
    renderTimeSlots();
    updateBookingSelection();
}