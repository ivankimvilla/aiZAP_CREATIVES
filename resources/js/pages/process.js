import { initCommon } from '../common';
import { initProcessPage } from '../modules/process-page';

const initPage = () => {
    initCommon();
    initProcessPage();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPage, { once: true });
} else {
    initPage();
}
