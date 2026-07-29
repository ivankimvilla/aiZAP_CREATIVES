export function initProjectThumbnailVideos() {
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
            // ignore browser limitations
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
                // ignore
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
                // ignore
            }

            try {
                video.play().catch(() => { });
            } catch (error) {
                // ignore
            }
        };

        updateToggle();
        if (shouldLazyLoad) {
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
                    // ignore
                }
            }
            updateToggle();
        });
    });
}

export function cleanupPortfolioVideoWork() {
    document.querySelectorAll('.project-thumb').forEach((thumb) => {
        const video = thumb.querySelector('video');
        if (!video) return;
        try {
            video.pause();
            video.dataset.videoClosed = '1';
            video.removeAttribute('src');
            video.querySelectorAll('source').forEach((source) => source.removeAttribute('src'));
            video.load();
        } catch (error) {
            // ignore
        }
    });
}
