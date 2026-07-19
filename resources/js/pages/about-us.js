import { initCommon } from '../common';

const initPage = () => {
    initCommon();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPage, { once: true });
} else {
    initPage();
}
