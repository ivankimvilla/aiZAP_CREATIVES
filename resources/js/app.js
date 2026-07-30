import './bootstrap';
import { cleanupPortfolioVideoWork, initProjectThumbnailVideos } from './modules/portfolio-videos';
import { initAdminVideoAudioCheck } from './modules/admin-video-audio';
import { initBookingCalendar } from './modules/booking-calendar';
import { initCommon } from './common';
import { initPricingPage } from './modules/pricing-page';
import { initGlobalMuteToggles } from './init-video-mutes';

window.addEventListener('pagehide', cleanupPortfolioVideoWork);
window.addEventListener('beforeunload', cleanupPortfolioVideoWork);

const initPageInteractions = () => {
    const pathname = window.location.pathname;
    const isPortfolioPage = pathname.includes('/portfolio');
    const isPricingPage = pathname.includes('/pricing');

    if (isPortfolioPage) {
        window.setTimeout(initProjectThumbnailVideos, 250);
        import('./pages/portfolio.js').catch(() => { });
    } else {
        initProjectThumbnailVideos();
    }

    if (isPricingPage) {
        try {
            initCommon();
            initPricingPage();
        } catch (e) {
            // Fallback to dynamic import if static init fails
            import('./pages/pricing.js').catch(() => { });
        }
    }

    initAdminVideoAudioCheck();
    initBookingCalendar();

    // Initialize global delegated mute toggles so category/home/portfolio all behave the same
    try {
        initGlobalMuteToggles();
    } catch (e) {
        // ignore
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPageInteractions, { once: true });
} else {
    initPageInteractions();
}
