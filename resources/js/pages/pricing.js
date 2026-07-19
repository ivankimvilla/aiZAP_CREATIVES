import { initCommon } from '../common';
import { initPricingPage } from '../modules/pricing-page';

const initPage = () => {
    initCommon();
    initPricingPage();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPage, { once: true });
} else {
    initPage();
}
