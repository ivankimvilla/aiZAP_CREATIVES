import { initCommon } from '../common';
import { initServicesShowMore } from '../modules/services';
import { initProjectThumbnailVideos, cleanupPortfolioVideoWork } from '../modules/portfolio-videos';

window.addEventListener('pagehide', cleanupPortfolioVideoWork);
window.addEventListener('beforeunload', cleanupPortfolioVideoWork);

const initPage = () => {
    initCommon();
    initServicesShowMore();
    window.setTimeout(initProjectThumbnailVideos, 250);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPage, { once: true });
} else {
    initPage();
}
