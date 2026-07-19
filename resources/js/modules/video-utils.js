export function ensureOverlayHost(thumb) {
    const pos = window.getComputedStyle(thumb).position;
    if (pos === 'static' || !pos) {
        thumb.style.position = 'relative';
    }
}

export function styleMuteToggle(toggle) {
    Object.assign(toggle.style, {
        position: 'absolute',
        right: '10px',
        bottom: '10px',
        zIndex: '5',
        width: '32px',
        height: '32px',
        lineHeight: '32px',
        textAlign: 'center',
        padding: '0',
        borderRadius: '50%',
        border: 'none',
        background: 'rgba(0, 0, 0, 0.6)',
        color: '#fff',
        fontSize: '15px',
        cursor: 'pointer',
    });
}

export function styleAudioIndicator(el) {
    Object.assign(el.style, {
        position: 'absolute',
        left: '10px',
        top: '10px',
        zIndex: '5',
        padding: '2px 8px',
        borderRadius: '999px',
        background: 'rgba(0, 0, 0, 0.6)',
        color: '#fff',
        fontSize: '11px',
        fontWeight: '600',
        letterSpacing: '0.02em',
        pointerEvents: 'none',
    });
}

export function ensureVideoPlaying(video) {
    if (!video || video.dataset.autoplayStarted === '1') return;

    try {
        video.dataset.autoplayStarted = '1';
        video.muted = true;
        video.volume = 0;
        video.setAttribute('muted', '');
        video.playsInline = true;
        video.setAttribute('playsinline', '');
        video.autoplay = true;
        video.setAttribute('autoplay', '');

        const playPromise = video.play();
        if (playPromise && typeof playPromise.then === 'function') {
            playPromise.catch(() => { });
        }
    } catch (e) {
        // ignore
    }
}

export function updateToggleIcon(toggle, video) {
    if (video.muted) {
        toggle.textContent = '🔇';
        toggle.setAttribute('aria-label', 'Unmute');
    } else {
        toggle.textContent = '🔊';
        toggle.setAttribute('aria-label', 'Mute');
    }
}

export function updateAudioIndicator(thumb, video, muted, hasAudio = null, isPortfolio = false) {
    let el = thumb.querySelector('.audio-indicator');
    if (!el) {
        el = document.createElement('div');
        el.className = 'audio-indicator';
        styleAudioIndicator(el);
        thumb.appendChild(el);
    }

    if (isPortfolio) {
        el.style.display = 'none';
        return;
    }

    if (muted) {
        el.textContent = 'Muted';
        el.classList.add('muted');
        el.classList.remove('no-audio');
    } else if (hasAudio === false) {
        el.textContent = 'No audio';
        el.classList.add('no-audio');
        el.classList.remove('muted');
    } else {
        el.textContent = 'Sound';
        el.classList.remove('muted');
        el.classList.remove('no-audio');
    }
}

export function detectAudio(video) {
    return new Promise((resolve) => {
        const maxAttempts = 15;
        let attempts = 0;

        const check = () => {
            attempts++;

            if (typeof video.webkitAudioDecodedByteCount === 'number') {
                if (video.webkitAudioDecodedByteCount > 0) {
                    resolve(true);
                    return;
                }
                if (video.currentTime > 0.5 || attempts >= maxAttempts) {
                    resolve(video.currentTime > 0 ? false : null);
                    return;
                }
                setTimeout(check, 200);
                return;
            }

            if (typeof video.mozHasAudio === 'boolean') {
                resolve(video.mozHasAudio);
                return;
            }

            if (video.audioTracks) {
                if (video.audioTracks.length > 0 || video.readyState >= 1) {
                    resolve(video.audioTracks.length > 0);
                    return;
                }
            }

            if (attempts >= maxAttempts) {
                resolve(null);
                return;
            }
            setTimeout(check, 200);
        };

        check();
    });
}
