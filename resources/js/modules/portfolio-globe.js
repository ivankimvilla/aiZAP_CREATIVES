import { ensureVideoPlaying } from './video-utils';

const globeObservers = [];
const globeTimers = [];

export function cleanupPortfolioGlobeWork() {
    globeObservers.splice(0).forEach((observer) => {
        if (observer && typeof observer.disconnect === 'function') {
            observer.disconnect();
        }
    });
    globeTimers.splice(0).forEach((id) => {
        if (id) window.clearTimeout(id);
    });
}

function loadGlobeVideoSource(video) {
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
        globeTimers.push(timerId);
    }
}

export function initPortfolioGlobe() {
    const placeholders = Array.from(document.querySelectorAll('.hero-collage-globe .collage-video-placeholder'));
    if (!placeholders.length) return;

    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;

                const placeholder = entry.target;
                if (placeholder.dataset.videoCreated === '1') return;

                const video = document.createElement('video');
                video.autoplay = true;
                video.muted = true;
                video.loop = true;
                video.playsInline = true;
                video.preload = 'metadata';
                video.volume = 0;

                const poster = placeholder.getAttribute('data-poster');
                if (poster) video.poster = poster;

                const source = document.createElement('source');
                source.type = 'video/mp4';
                video.appendChild(source);
                placeholder.textContent = '';
                placeholder.appendChild(video);

                video.setAttribute('data-src', placeholder.getAttribute('data-src'));
                placeholder.dataset.videoCreated = '1';
                loadGlobeVideoSource(video);
                observer.unobserve(placeholder);
            });
        }, { rootMargin: '160px 0px', threshold: 0.1 });

        globeObservers.push(observer);
        placeholders.forEach((placeholder) => observer.observe(placeholder));
    } else {
        placeholders.forEach((placeholder) => {
            if (placeholder.dataset.videoCreated === '1') return;

            const video = document.createElement('video');
            video.autoplay = true;
            video.muted = true;
            video.loop = true;
            video.playsInline = true;
            video.preload = 'metadata';
            video.volume = 0;

            const poster = placeholder.getAttribute('data-poster');
            if (poster) video.poster = poster;

            const source = document.createElement('source');
            source.type = 'video/mp4';
            video.appendChild(source);
            placeholder.textContent = '';
            placeholder.appendChild(video);

            video.setAttribute('data-src', placeholder.getAttribute('data-src'));
            placeholder.dataset.videoCreated = '1';
            loadGlobeVideoSource(video);
        });
    }
}
