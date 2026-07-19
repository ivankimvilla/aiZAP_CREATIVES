import {
    ensureOverlayHost,
    styleMuteToggle,
    ensureVideoPlaying,
    updateAudioIndicator,
    updateToggleIcon,
    detectAudio,
} from './video-utils';

const portfolioVideoObservers = [];
const portfolioVideoTimers = [];

export function cleanupPortfolioVideoWork() {
    portfolioVideoObservers.splice(0).forEach((observer) => {
        if (observer && typeof observer.disconnect === 'function') {
            observer.disconnect();
        }
    });
    portfolioVideoTimers.splice(0).forEach((id) => {
        if (id) window.clearTimeout(id);
    });
}

function loadVideoSource(video) {
    const src = video.getAttribute('data-src');
    if (src) {
        const source = video.querySelector('source');
        if (source) {
            source.src = src;
        } else {
            video.src = src;
        }
    }

    try {
        video.load();
    } catch (e) {
        // ignore
    }

    if (!video.dataset.playQueued) {
        video.dataset.playQueued = '1';
        const timerId = window.setTimeout(() => ensureVideoPlaying(video), 80);
        portfolioVideoTimers.push(timerId);
    }
}

function setVideoMuteState(video, toggle, thumb, muted, isPortfolioPage) {
    try {
        if (muted) {
            video.muted = true;
            video.volume = 0;
            video.setAttribute('muted', '');
        } else {
            video.muted = false;
            video.volume = 1;
            video.removeAttribute('muted');
        }
    } catch (e) {
        // ignore
    }

    if (toggle) {
        updateToggleIcon(toggle, video);
    }

    updateAudioIndicator(thumb, video, video.muted, null, isPortfolioPage);
}

function muteAllOtherVideos(activeThumb, isPortfolioPage, activeVideo = null) {
    document.querySelectorAll('.project-thumb').forEach((thumb) => {
        const video = thumb.querySelector('video');
        const toggle = thumb.querySelector('.mute-toggle');
        if (!video) return;
        if (activeVideo && video === activeVideo) return;
        if (thumb === activeThumb) return;

        setVideoMuteState(video, toggle, thumb, true, isPortfolioPage);
    });
}

function muteAllOtherVideosByVideo(activeVideo, isPortfolioPage) {
    document.querySelectorAll('.project-thumb').forEach((thumb) => {
        const video = thumb.querySelector('video');
        const toggle = thumb.querySelector('.mute-toggle');
        if (!video || video === activeVideo) return;

        setVideoMuteState(video, toggle, thumb, true, isPortfolioPage);
    });
}

function activateVideoAudio(video, toggle, thumb, isPortfolioPage) {
    muteAllOtherVideos(thumb, isPortfolioPage, video);
    setVideoMuteState(video, toggle, thumb, false, isPortfolioPage);

    if (video.paused) {
        video.play().catch(() => { });
    }

    detectAudio(video).then((hasAudio) => {
        updateAudioIndicator(thumb, video, false, hasAudio, isPortfolioPage);
    });
}

export function initProjectThumbnailVideos() {
    const isPortfolioPage = window.location.pathname.includes('/portfolio');

    document.querySelectorAll('.project-thumb').forEach((thumb) => {
        if (thumb.dataset.videoBound === '1') return;
        thumb.dataset.videoBound = '1';

        const video = thumb.querySelector('video');
        const toggle = thumb.querySelector('.mute-toggle');
        if (!video) return;

        const hasDataSrc = Boolean(video.getAttribute('data-src'));
        const shouldLazyLoad = hasDataSrc && !video.getAttribute('src') && !video.src;

        ensureOverlayHost(thumb);
        if (toggle) styleMuteToggle(toggle);

        const loadVideoIfNeeded = () => {
            if (video.dataset.videoLoaded === '1') return;
            video.dataset.videoLoaded = '1';
            loadVideoSource(video);
        };

        try {
            video.muted = true;
            video.setAttribute('muted', '');
            video.playsInline = true;
            video.setAttribute('playsinline', '');
            video.autoplay = true;
            video.setAttribute('autoplay', '');
        } catch (e) {
            // ignore
        }

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
                portfolioVideoObservers.push(observer);
                observer.observe(thumb);
            } else {
                window.setTimeout(loadVideoIfNeeded, 120);
            }
        } else {
            window.setTimeout(loadVideoIfNeeded, 60);
        }

        if (!isPortfolioPage) {
            updateAudioIndicator(thumb, video, video.muted, null, false);
            detectAudio(video).then((hasAudio) => {
                updateAudioIndicator(thumb, video, video.muted, hasAudio, false);
            });
        } else {
            updateAudioIndicator(thumb, video, video.muted, null, true);
        }

        video.addEventListener('loadedmetadata', () => {
            thumb.classList.add('video-loaded');
        });

        if (toggle) {
            const updateIcon = () => updateToggleIcon(toggle, video);
            updateIcon();

            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                if (video.muted) {
                    activateVideoAudio(video, toggle, thumb, isPortfolioPage);
                    muteAllOtherVideosByVideo(video, isPortfolioPage);
                } else {
                    setVideoMuteState(video, toggle, thumb, true, isPortfolioPage);
                }
                updateIcon();
            });
        }

        video.addEventListener('play', () => {
            if (!video.muted) {
                muteAllOtherVideosByVideo(video, isPortfolioPage);
            }
        });

        video.addEventListener('volumechange', () => {
            if (!video.muted) {
                muteAllOtherVideosByVideo(video, isPortfolioPage);
            }
        });

        if (!isPortfolioPage) {
            try {
                const keepPlaying = setInterval(() => {
                    if (video && video.paused) {
                        video.play().catch(() => { });
                    }
                }, 2000);
                video._keepPlayingInterval = keepPlaying;
            } catch (e) {
                // ignore
            }
        }
    });
}
