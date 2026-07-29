document.addEventListener('DOMContentLoaded', function () {
    function qs(sel, el = document) { return el.querySelector(sel); }
    function qsa(sel, el = document) { return Array.from(el.querySelectorAll(sel)); }

    const modal = qs('#video-modal');
    if (!modal) return;
    const dialog = qs('.video-modal__dialog', modal);
    const content = qs('.video-modal__content', modal);
    const closeBtn = qs('.video-modal__close', modal);

    function getVideoSource(video) {
        if (!video) return '';

        const dataSrc = video.getAttribute('data-src');
        if (dataSrc) return dataSrc;

        const directSrc = video.getAttribute('src');
        if (directSrc) return directSrc;

        const source = video.querySelector('source');
        if (source) {
            const sourceSrc = source.getAttribute('src');
            if (sourceSrc) return sourceSrc;
        }

        return video.currentSrc || '';
    }

    function openModalWithSrc(src, poster) {
        if (!src) return;

        // clear
        content.innerHTML = '';
        const vid = document.createElement('video');
        vid.src = src;
        if (poster) vid.poster = poster;
        vid.controls = true;
        vid.autoplay = true;
        vid.playsInline = true;
        // Start unmuted so user hears audio when allowed; native controls include mute toggle
        vid.muted = false;
        vid.style.maxWidth = '100%';
        vid.style.maxHeight = '80vh';
        content.appendChild(vid);

        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('video-modal-open');
        vid.focus();
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
    }

    function updateMuteButton(button, video) {
        if (!button || !video) return;
        const muted = Boolean(video.muted);
        button.textContent = muted ? '🔇' : '🔊';
        button.setAttribute('aria-label', muted ? 'Unmute' : 'Mute');
    }

    // button interactions
    document.addEventListener('click', (e) => {
        const muteBtn = e.target.closest('.mute-toggle');
        if (muteBtn) {
            e.preventDefault();
            e.stopPropagation();
            const container = muteBtn.closest('article, .project-card, .value-card, .category-item, .project-item, .pf-project-card, .pf-project-thumb, .project-thumb');
            const video = container ? container.querySelector('video') : null;
            if (!video) return;
            video.muted = !video.muted;
            updateMuteButton(muteBtn, video);
            return;
        }

        const btn = e.target.closest('.expand-btn, .expand-toggle');
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();
        const container = btn.closest('article, .project-card, .value-card, .category-item, .project-item, .pf-project-card, .pf-project-thumb, .project-thumb');
        const video = container ? container.querySelector('video') : null;

        let src = '';
        let poster = '';
        if (video) {
            src = getVideoSource(video);
            poster = video.getAttribute('poster');
        } else if (btn.dataset && btn.dataset.src) {
            src = btn.dataset.src;
        }

        if (!src) return;
        if (window.console && console.log) console.log('video-modal: expand clicked', src, poster);
        openModalWithSrc(src, poster);
    });

    // close interactions
    modal.addEventListener('click', (e) => {
        if (e.target.matches('[data-close]') || e.target === closeBtn) closeModal();
    });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeModal(); });
});