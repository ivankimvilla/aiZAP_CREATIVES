import { initCommon } from '../common';
import { initServicesShowMore } from '../modules/services';

const initPage = () => {
    initCommon();
    initServicesShowMore();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPage, { once: true });
} else {
    initPage();
}
