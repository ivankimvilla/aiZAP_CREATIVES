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

    qsa('.expand-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const container = btn.closest('article, .project-card, .value-card, .category-item, .project-item');
            const video = container ? container.querySelector('video') : null;
            if (!video) return;
            const src = video.getAttribute('data-src') || video.currentSrc || video.getAttribute('src');
            const poster = video.getAttribute('poster');
            openModalWithSrc(src, poster);
        });
    });

    modal.addEventListener('click', (e) => {
        if (e.target.matches('[data-close]') || e.target === closeBtn) closeModal();
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
});
