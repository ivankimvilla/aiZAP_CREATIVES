import { detectAudio } from './video-utils';

export function initAdminVideoAudioCheck() {
    const fileInput = document.querySelector('form input[type="file"][name="video"]');
    if (!fileInput) return;

    fileInput.addEventListener('change', async (e) => {
        const file = e.target.files && e.target.files[0];
        let container = fileInput.closest('form').querySelector('.video-audio-check');
        if (!container) {
            container = document.createElement('div');
            container.className = 'video-audio-check';
            fileInput.closest('form').insertBefore(container, fileInput.nextSibling);
        }
        container.textContent = 'Checking audio…';

        if (!file) {
            container.textContent = '';
            return;
        }

        const url = URL.createObjectURL(file);
        const tempVideo = document.createElement('video');
        tempVideo.src = url;
        tempVideo.muted = true;
        tempVideo.playsInline = true;
        tempVideo.preload = 'auto';
        tempVideo.style.position = 'fixed';
        tempVideo.style.left = '-9999px';
        document.body.appendChild(tempVideo);

        await new Promise((resolve) => {
            const timer = setTimeout(resolve, 1500);
            tempVideo.addEventListener('loadedmetadata', () => {
                clearTimeout(timer);
                resolve();
            }, { once: true });
        });

        tempVideo.play().catch(() => { });

        const hasAudio = await detectAudio(tempVideo);
        if (hasAudio === false) {
            container.textContent = 'Warning: No audio detected in this file.';
            container.classList.add('no-audio');
        } else if (hasAudio === true) {
            container.textContent = 'Audio detected.';
            container.classList.remove('no-audio');
        } else {
            container.textContent = '';
        }

        try {
            URL.revokeObjectURL(url);
        } catch (e) {
            // ignore
        }
        tempVideo.remove();
    });
}
