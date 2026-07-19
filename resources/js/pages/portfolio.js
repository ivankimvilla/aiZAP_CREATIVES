import { initCommon } from '../common';
import { initProjectThumbnailVideos, cleanupPortfolioVideoWork } from '../modules/portfolio-videos';
import { initPortfolioGlobe, cleanupPortfolioGlobeWork } from '../modules/portfolio-globe';

window.addEventListener('pagehide', () => {
    cleanupPortfolioVideoWork();
    cleanupPortfolioGlobeWork();
});
window.addEventListener('beforeunload', () => {
    cleanupPortfolioVideoWork();
    cleanupPortfolioGlobeWork();
});

const initPage = () => {
    initCommon();
    initPortfolioGlobe();
    window.setTimeout(initProjectThumbnailVideos, 250);
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPage, { once: true });
} else {
    initPage();
}
