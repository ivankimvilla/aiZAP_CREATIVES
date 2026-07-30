// Delegated mute toggle handler
// Ensures only one video is unmuted at a time and keeps toggle UI in sync.
export function initGlobalMuteToggles() {
    if (typeof document === 'undefined') return;

    document.addEventListener('click', (e) => {
        const btn = e.target.closest && e.target.closest('.mute-toggle');
        if (!btn) return;
        e.preventDefault();
        e.stopPropagation();

        const container = btn.closest('article, .project-card, .value-card, .category-item, .project-item, .pf-project-card, .pf-project-thumb, .project-thumb');
        const video = container ? container.querySelector('video') : null;
        if (!video) return;

        const willBeMuted = !video.muted;

        const updateBtn = (toggleEl, vid) => {
            if (!toggleEl || !vid) return;
            toggleEl.textContent = vid.muted ? '🔇' : '🔊';
            toggleEl.setAttribute('aria-label', vid.muted ? 'Unmute' : 'Mute');
        };

        const muteAllOtherVideos = (activeVideo) => {
            document.querySelectorAll('video').forEach((other) => {
                if (!other || other === activeVideo) return;
                try {
                    other.muted = true;
                    other.volume = 0;
                    other.setAttribute('muted', '');
                } catch (err) {
                    // ignore
                }
                const otherToggle = other.closest && other.closest('article, .project-card, .value-card, .category-item, .project-item, .pf-project-card, .pf-project-thumb, .project-thumb')?.querySelector('.mute-toggle');
                if (otherToggle) updateBtn(otherToggle, other);
            });
        };

        if (willBeMuted) {
            try {
                video.muted = true;
                video.volume = 0;
                video.setAttribute('muted', '');
            } catch (err) {
                // ignore
            }
            updateBtn(btn, video);
        } else {
            muteAllOtherVideos(video);
            try {
                video.muted = false;
                video.volume = 1;
                video.removeAttribute('muted');
                video.play && video.play().catch(() => { });
            } catch (err) {
                // ignore
            }
            updateBtn(btn, video);
        }
    });
}
