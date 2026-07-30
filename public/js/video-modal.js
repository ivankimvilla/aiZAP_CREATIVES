// Public fallback build copy of resources/js/video-modal.js
document.addEventListener('DOMContentLoaded', function () {
    function qs(sel, el = document) { return el.querySelector(sel); }
    function qsa(sel, el = document) { return Array.from(el.querySelectorAll(sel)); }

    const modal = qs('#video-modal');
    if (!modal) return;
    const dialog = qs('.video-modal__dialog', modal);
    const content = qs('.video-modal__content', modal);
    const closeBtn = qs('.video-modal__close', modal);

    function openModalWithSrc(src, poster) {
        content.innerHTML = '';
        const vid = document.createElement('video');
        vid.src = src;
        if (poster) vid.poster = poster;
        vid.controls = true;
        vid.autoplay = true;
        vid.playsInline = true;
        vid.style.maxWidth = '100%';
        vid.style.maxHeight = '80vh';
        content.appendChild(vid);

        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('video-modal-open');
        vid.focus();
        // update modal mute button state
        const muteBtn = qs('.video-modal__mute', modal);
        if (muteBtn) {
            const updateIcon = () => { muteBtn.textContent = vid.muted ? '🔇' : '🔊'; };
            updateIcon();
            muteBtn.onclick = () => {
                vid.muted = !vid.muted;
                updateIcon();
            };
        }
    }

    function closeModal() {
        const v = qs('video', content);
        if (v) {
            v.pause();
            v.src = '';
        }
        content.innerHTML = '';
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('video-modal-open');
        const muteBtn = qs('.video-modal__mute', modal);
        if (muteBtn) muteBtn.textContent = '🔇';
    }

    qsa('.expand-btn, .expand-toggle').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            const container = btn.closest('article, .project-card, .value-card, .category-item, .project-item');
            const video = container ? container.querySelector('video') : null;
            if (!video) return;
            const src = video.getAttribute('data-src') || video.currentSrc || video.getAttribute('src');
            const poster = video.getAttribute('poster');
            openModalWithSrc(src, poster);
        });
    });

    // Global mute toggle handler — ensures only one video is unmuted at a time
    document.addEventListener('click', (e) => {
        const muteBtn = e.target.closest('.mute-toggle');
        if (!muteBtn) return;
        e.preventDefault();
        e.stopPropagation();
        const container = muteBtn.closest('article, .project-card, .value-card, .category-item, .project-item, .pf-project-card, .pf-project-thumb, .project-thumb');
        const video = container ? container.querySelector('video') : null;
        if (!video) return;

        const muteAllOtherVideos = (activeVideo) => {
            document.querySelectorAll('video').forEach((otherVideo) => {
                if (!otherVideo || otherVideo === activeVideo) return;
                try {
                    otherVideo.muted = true;
                    otherVideo.volume = 0;
                    otherVideo.setAttribute('muted', '');
                } catch (err) {
                    // ignore
                }
                const otherContainer = otherVideo.closest('article, .project-card, .value-card, .category-item, .project-item, .pf-project-card, .pf-project-thumb, .project-thumb');
                const otherToggle = otherContainer ? otherContainer.querySelector('.mute-toggle') : null;
                if (otherToggle) {
                    otherToggle.textContent = otherVideo.muted ? '🔇' : '🔊';
                    otherToggle.setAttribute('aria-label', otherVideo.muted ? 'Unmute' : 'Mute');
                }
            });
        };

        const willBeMuted = !video.muted;
        if (willBeMuted) {
            try {
                video.muted = true;
                video.volume = 0;
                video.setAttribute('muted', '');
            } catch (err) {
                // ignore
            }
            muteBtn.textContent = '🔇';
            muteBtn.setAttribute('aria-label', 'Unmute');
        } else {
            // Unmuting: mute all others first, then unmute this one
            muteAllOtherVideos(video);
            try {
                video.muted = false;
                video.volume = 1;
                video.removeAttribute('muted');
                video.play().catch(() => { });
            } catch (err) {
                // ignore
            }
            muteBtn.textContent = '🔊';
            muteBtn.setAttribute('aria-label', 'Mute');
        }
    });

    modal.addEventListener('click', (e) => {
        if (e.target.matches('[data-close]') || e.target === closeBtn) closeModal();
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
});
