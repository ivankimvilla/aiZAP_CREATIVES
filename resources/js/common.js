import './bootstrap';
import { initContactDropdown } from './modules/contact-dropdown';
import { initBookingCalendar } from './modules/booking-calendar';

export function initCommon() {
    initContactDropdown();
    initBookingCalendar();
}
