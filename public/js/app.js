(function () {
    const pathname = window.location.pathname || '';
    const body = document.body;
    const className = body ? body.className : '';
    const isPortfolioPage = pathname.includes('/portfolio') || className.includes('portfolio-page');
    const isPricingPage = pathname.includes('/pricing') || className.includes('pricing-page');
    const isProcessPage = pathname.includes('/process') || className.includes('process-page');

    function initServicesShowMore() {
        const grid = document.querySelector('.services-grid');
        if (!grid) return;
        const items = Array.from(grid.querySelectorAll('.service-item'));
        const max = 8;
        if (items.length <= max) return;

        items.forEach((item, index) => {
            if (index >= max) item.classList.add('service-item--hidden');
        });

        const button = document.getElementById('show-more-services');
        if (!button) return;

        button.addEventListener('click', (event) => {
            event.preventDefault();
            const expanded = grid.classList.toggle('services-grid--expanded');
            if (expanded) {
                items.forEach((item) => item.classList.remove('service-item--hidden'));
                button.textContent = 'Show Less';
            } else {
                items.forEach((item, index) => {
                    if (index >= max) item.classList.add('service-item--hidden');
                });
                button.textContent = 'Show More';
            }
        });
    }

    function initProjectThumbs() {
        if (!document.querySelector('.project-thumb')) return;

        document.querySelectorAll('.project-thumb').forEach((thumb) => {
            if (thumb.dataset.videoBound === '1') return;
            thumb.dataset.videoBound = '1';

            const video = thumb.querySelector('video');
            const toggle = thumb.querySelector('.mute-toggle');
            if (!video || !toggle) return;

            const hasDataSrc = Boolean(video.getAttribute('data-src'));
            const shouldLazyLoad = hasDataSrc && !video.getAttribute('src') && !video.src;

            try {
                video.muted = true;
                video.setAttribute('muted', '');
                video.playsInline = true;
                video.setAttribute('playsinline', '');
                video.autoplay = true;
                video.setAttribute('autoplay', '');
            } catch (error) {
            }

            const closeBtn = thumb.querySelector('.pf-video-close');

            const setExpanded = () => {
                const card = thumb.closest('.pf-project-card');
                if (card) card.classList.add('is-video-expanded');
                if (closeBtn) closeBtn.style.display = 'flex';
            };

            const updateToggle = () => {
                toggle.textContent = video.muted ? '🔇' : '🔊';
                toggle.setAttribute('aria-label', video.muted ? 'Unmute' : 'Mute');
            };

            const closeVideo = () => {
                const card = thumb.closest('.pf-project-card');
                if (card) card.classList.remove('is-video-expanded');
                if (closeBtn) closeBtn.style.display = 'none';
                try {
                    video.pause();
                    video.currentTime = 0;
                } catch (error) {
                }
            };

            if (closeBtn) {
                closeBtn.addEventListener('click', (event) => {
                    event.preventDefault();
                    event.stopPropagation();
                    closeVideo();
                });
            }

            if (thumb) {
                thumb.addEventListener('pointerdown', () => setExpanded(), { passive: true });
            }

            const muteAllOtherPortfolioVideos = (activeVideo) => {
                document.querySelectorAll('.project-thumb').forEach((otherThumb) => {
                    const otherVideo = otherThumb.querySelector('video');
                    const otherToggle = otherThumb.querySelector('.mute-toggle');
                    if (!otherVideo || otherVideo === activeVideo) return;
                    otherVideo.muted = true;
                    otherVideo.volume = 0;
                    otherVideo.setAttribute('muted', '');
                    if (otherToggle) {
                        otherToggle.textContent = '🔇';
                        otherToggle.setAttribute('aria-label', 'Unmute');
                    }
                });
            };

            const loadVideoIfNeeded = () => {
                if (video.dataset.videoLoaded === '1' || video.dataset.videoClosed === '1') return;
                video.dataset.videoLoaded = '1';

                if (video.getAttribute('data-src')) {
                    const source = video.querySelector('source');
                    if (source) {
                        source.src = video.getAttribute('data-src');
                    } else {
                        video.src = video.getAttribute('data-src');
                    }
                }

                try {
                    video.load();
                } catch (error) {
                }

                try {
                    video.play().catch(() => { });
                } catch (error) {
                }
            };

            updateToggle();
            if (shouldLazyLoad && isPortfolioPage) {
                if ('IntersectionObserver' in window) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach((entry) => {
                            if (entry.isIntersecting) {
                                loadVideoIfNeeded();
                                observer.disconnect();
                            }
                        });
                    }, { rootMargin: '160px 0px', threshold: 0.1 });
                    observer.observe(thumb);
                } else {
                    window.setTimeout(loadVideoIfNeeded, 120);
                }
            } else {
                window.setTimeout(loadVideoIfNeeded, 60);
            }

            const ensureOnlyOneAudioSource = () => {
                if (!video.muted) {
                    muteAllOtherPortfolioVideos(video);
                }
            };

            video.addEventListener('play', ensureOnlyOneAudioSource);
            video.addEventListener('volumechange', ensureOnlyOneAudioSource);

            toggle.addEventListener('click', (event) => {
                event.preventDefault();
                const willBeMuted = !video.muted;
                if (willBeMuted) {
                    video.muted = true;
                    video.volume = 0;
                    video.setAttribute('muted', '');
                } else {
                    muteAllOtherPortfolioVideos(video);
                    video.muted = false;
                    video.volume = 1;
                    video.removeAttribute('muted');
                    try {
                        video.play();
                    } catch (error) {
                    }
                }
                updateToggle();
            });
        });
    }

    function initPricingPage() {
        if (!isPricingPage) return;

        const glow = document.querySelector('.pricing-heading-glow');
        if (glow) {
            glow.addEventListener('pointermove', (event) => {
                const rect = glow.getBoundingClientRect();
                glow.style.setProperty('--glow-x', `${event.clientX - rect.left}px`);
                glow.style.setProperty('--glow-y', `${event.clientY - rect.top}px`);
            });
        }

        const cards = document.querySelectorAll('.pricing-card');
        const overlay = document.getElementById('pricingOverlay');
        const pricingPanel = document.getElementById('pricingInquiryPanel');
        if (!cards.length || !overlay || !pricingPanel) return;

        const pricingPlans = (() => {
            try {
                return JSON.parse(overlay.dataset.plans || '{}');
            } catch (error) {
                return {};
            }
        })();

        let selectedCard = null;

        // ADDED — this was missing, and its absence was the actual bug.
        // Calling an undefined function threw a ReferenceError that halted
        // the rest of initPricingPage(), so closeOverlay() never ran.
        const animateToast = (toast, delay = 3200) => {
            if (!toast) return;
            requestAnimationFrame(() => toast.classList.add('show'));
            setTimeout(() => {
                toast.classList.add('fade-out');
                setTimeout(() => toast.remove(), 360);
            }, delay);
        };

        const getSummaryElements = () => {
            return {
                title: pricingPanel.querySelector('.pricing-inquiry-header .section-title'),
                subtitle: pricingPanel.querySelector('.pricing-inquiry-header .summary-subtitle'),
                featuresEl: pricingPanel.querySelector('.summary-features'),
                packageInput: pricingPanel.querySelector('.pricing-inquiry-form input[name="package"]'),
            };
        };

        const updatePricingSummary = (packageValue) => {
            const { title, subtitle, featuresEl, packageInput } = getSummaryElements();
            const meta = pricingPlans[packageValue] || null;

            if (title && packageValue) {
                title.textContent = packageValue;
            }

            if (packageInput) {
                packageInput.value = packageValue;
            }

            if (subtitle) {
                subtitle.textContent = meta ? meta.subtitle : 'Submit your package request and we’ll respond with pricing details.';
            }

            if (featuresEl) {
                featuresEl.innerHTML = '';
                if (meta && Array.isArray(meta.items)) {
                    meta.items.forEach((item) => {
                        const li = document.createElement('li');
                        li.textContent = item;
                        featuresEl.appendChild(li);
                    });
                } else {
                    ['Concepts & AI video production', 'Platform-ready formats', 'Revisions & delivery timeline'].forEach((item) => {
                        const li = document.createElement('li');
                        li.textContent = item;
                        featuresEl.appendChild(li);
                    });
                }
            }
        };

        const clearActiveState = () => {
            cards.forEach((card) => card.classList.remove('is-active', 'is-selected'));
            document.querySelectorAll('.pricing-card .pricing-select-button').forEach((button) => {
                button.classList.remove('is-active');
                button.setAttribute('aria-pressed', 'false');
            });
        };

        const openOverlay = () => {
            overlay.hidden = false;
            overlay.classList.add('is-open');
            pricingPanel.classList.add('is-open');
        };

        const closeOverlay = () => {
            overlay.classList.remove('is-open');
            pricingPanel.classList.remove('is-open');
            overlay.hidden = true;
        };

        const activateCard = (card, persistent, withOverlay = false) => {
            if (!card) return;
            clearActiveState();
            card.classList.add('is-active');
            if (persistent) {
                card.classList.add('is-selected');
                selectedCard = card;
            }
            const button = card.querySelector('.pricing-select-button');
            if (button) {
                button.classList.add('is-active');
                button.setAttribute('aria-pressed', 'true');
            }
            const packageValue = card.querySelector('.pricing-select-button')?.dataset.plan || '';
            if (packageValue) {
                updatePricingSummary(packageValue);
            }
            if (withOverlay) {
                openOverlay();
            }
        };

        const openOverlayForPlan = (packageValue, triggerButton) => {
            const button = triggerButton || document.querySelector(`.pricing-select-button[data-plan="${packageValue}"]`);
            const card = button ? button.closest('.pricing-card') : null;
            if (!card || !packageValue) return;

            button.classList.add('is-active');
            button.setAttribute('aria-pressed', 'true');
            activateCard(card, true, true);

            const nameInput = pricingPanel.querySelector('.pricing-inquiry-form input[name="name"]');
            if (nameInput) {
                nameInput.focus();
            }

            if (window.innerWidth < 900) {
                pricingPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        };

        cards.forEach((card) => {
            card.addEventListener('mouseenter', () => activateCard(card, false));
            card.addEventListener('mouseleave', (event) => {
                const relatedCard = event.relatedTarget && event.relatedTarget.closest
                    ? event.relatedTarget.closest('.pricing-card')
                    : null;
                if (relatedCard) return;
                if (selectedCard) {
                    activateCard(selectedCard, true);
                } else {
                    clearActiveState();
                }
            });

            const button = card.querySelector('.pricing-select-button');
            if (button) {
                button.addEventListener('click', (event) => {
                    event.preventDefault();
                    openOverlayForPlan(button.dataset.plan || '', button);
                });
            }
        });

        const closeButton = pricingPanel.querySelector('.pricing-close');
        if (closeButton) {
            closeButton.addEventListener('click', (event) => {
                event.preventDefault();
                closeOverlay();
            });
        }

        const summaryCancel = pricingPanel.querySelector('.pricing-summary-cancel');
        if (summaryCancel) {
            summaryCancel.addEventListener('click', (event) => {
                event.preventDefault();
                clearActiveState();
                const packageInput = pricingPanel.querySelector('.pricing-inquiry-form input[name="package"]');
                if (packageInput) packageInput.value = '';
                closeOverlay();
            });
        }

        overlay.addEventListener('click', (event) => {
            if (event.target === overlay) {
                closeOverlay();
            }
        });

        document.addEventListener('keydown', (event) => {
            if ((event.key === 'Escape' || event.key === 'Esc') && overlay.classList.contains('is-open')) {
                closeOverlay();
            }
        });

        const initialPackage = overlay.dataset.initialPackage || '';
        const serverOpen = overlay.dataset.serverOpen === '1';
        if (initialPackage) {
            openOverlayForPlan(initialPackage);
        } else if (serverOpen) {
            openOverlay();
        }

        // FIXED — close first (unconditionally, synchronously), then animate the toast.
        // Also hides the leftover server-rendered success/error banners so they
        // don't reappear if the panel is opened again later in the same session.
        const pricingToast = document.getElementById('pricingSuccessToast');
        if (pricingToast) {
            closeOverlay();
            animateToast(pricingToast, 3400);

            document.querySelectorAll('.pricing-form-success:not([id]), .pricing-form-errors:not([id])').forEach((el) => {
                el.hidden = true;
            });
        }
    }

    function initProcessPage() {
        if (!isProcessPage) return;

        document.querySelectorAll('.process-scroll-wrap').forEach((wrap) => {
            const el = wrap.querySelector('.process-scroll');
            const arrow = wrap.querySelector('.process-scroll-arrow');
            if (!el) return;

            const items = Array.from(el.querySelectorAll('.process-scroll-item'));
            let isDown = false;
            let startX = 0;
            let startScroll = 0;

            const updateCenterItem = () => {
                if (!items.length) return;
                const containerRect = el.getBoundingClientRect();
                const containerCenter = containerRect.left + containerRect.width / 2;
                let closest = null;
                let closestDist = Infinity;

                items.forEach((item) => {
                    const rect = item.getBoundingClientRect();
                    const itemCenter = rect.left + rect.width / 2;
                    const dist = Math.abs(itemCenter - containerCenter);
                    if (dist < closestDist) {
                        closestDist = dist;
                        closest = item;
                    }
                });

                items.forEach((item) => item.classList.toggle('is-center', item === closest));
            };

            if (items.length) {
                const middleItem = items[Math.floor((items.length - 1) / 2)];
                el.scrollLeft = middleItem.offsetLeft + middleItem.offsetWidth / 2 - el.clientWidth / 2;
            }

            updateCenterItem();
            el.addEventListener('scroll', updateCenterItem, { passive: true });
            window.addEventListener('resize', updateCenterItem);

            el.addEventListener('pointerdown', (event) => {
                isDown = true;
                startX = event.clientX;
                startScroll = el.scrollLeft;
                el.setPointerCapture(event.pointerId);
            });

            el.addEventListener('pointermove', (event) => {
                if (!isDown) return;
                el.scrollLeft = startScroll - (event.clientX - startX);
            });

            ['pointerup', 'pointercancel', 'pointerleave'].forEach((eventName) => {
                el.addEventListener(eventName, () => {
                    isDown = false;
                });
            });

            if (arrow) {
                arrow.addEventListener('click', () => {
                    const atEnd = el.scrollLeft + el.clientWidth >= el.scrollWidth - 8;
                    const step = Math.max(el.clientWidth * 0.6, 220);
                    el.scrollTo({
                        left: atEnd ? 0 : el.scrollLeft + step,
                        behavior: 'smooth',
                    });
                });
            }
        });
    }

    function initContactDropdown() {
        const dropdown = document.getElementById('contactDropdown');
        if (!dropdown || dropdown.dataset.contactBound === '1') return;
        dropdown.dataset.contactBound = '1';

        const contactButtons = document.querySelectorAll('.contact-toggle');
        const closeBtn = dropdown.querySelector('.contact-dropdown-close');

        const setOpen = (open) => {
            dropdown.classList.toggle('open', open);
            dropdown.setAttribute('aria-hidden', open ? 'false' : 'true');
        };

        const showToast = (message) => {
            const toast = document.createElement('div');
            toast.className = 'contact-toast';
            toast.setAttribute('role', 'status');
            toast.innerHTML = `
                <div class="contact-toast-content">
                    <span class="contact-toast-icon">&#10003;</span>
                    <div>${message}</div>
                </div>
            `;
            document.body.appendChild(toast);
            requestAnimationFrame(() => toast.classList.add('show'));
            setTimeout(() => {
                toast.classList.add('fade-out');
                setTimeout(() => {
                    toast.remove();
                }, 360);
            }, 3200);
        };

        const toggleDropdown = (event) => {
            event.preventDefault();
            event.stopPropagation();
            setOpen(!dropdown.classList.contains('open'));
        };

        if (contactButtons.length) {
            contactButtons.forEach((button) => {
                button.addEventListener('click', toggleDropdown);
            });
        }
        if (closeBtn) {
            closeBtn.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                setOpen(false);
            });
        }

        document.addEventListener('click', (event) => {
            if (!dropdown.classList.contains('open')) return;
            if (event.target.closest('.contact-dropdown') || event.target.closest('.contact-float-button')) return;
            setOpen(false);
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && dropdown.classList.contains('open')) {
                setOpen(false);
            }
        });

        const statusEl = dropdown.querySelector('#contactDropdownStatus');
        const statusMessage = statusEl ? statusEl.textContent.trim() : '';

        if (statusMessage) {
            statusEl.classList.add('fade-out');
            setOpen(false);
            showToast(statusMessage);
        } else if (dropdown.dataset.initialOpen === 'true') {
            setOpen(true);
            const firstInput = dropdown.querySelector('input, textarea');
            if (firstInput) firstInput.focus();
        }
    }

    // Helper to format time in 12-hour format (e.g., "5:27 PM")
    function format12HourTime(hours, minutes) {
        const h = parseInt(hours, 10);
        const m = String(minutes).padStart(2, '0');
        const period = h >= 12 ? 'PM' : 'AM';
        const displayHour = h === 0 ? 12 : h > 12 ? h - 12 : h;
        return `${displayHour}:${m} ${period}`;
    }

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

    // Fetch availability for a specific date and timezone
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

    // Update time slot disabled states based on the availability payload
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

    function initBookingCalendar() {
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

    const initPageInteractions = () => {
        initServicesShowMore();
        initProjectThumbs();
        initPricingPage();
        initProcessPage();
        initContactDropdown();
        initBookingCalendar();
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPageInteractions, { once: true });
    } else {
        initPageInteractions();
    }
})();