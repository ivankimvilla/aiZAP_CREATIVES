export function initProcessPage() {
    document.querySelectorAll('.process-scroll-wrap').forEach((wrap) => {
        const el = wrap.querySelector('.process-scroll');
        const arrow = wrap.querySelector('.process-scroll-arrow');
        if (!el) return;

        const items = Array.from(el.querySelectorAll('.process-scroll-item'));
        let isDown = false;
        let startX = 0;
        let startScroll = 0;
        let hideTimer = null;
        let ticking = false;

        const scheduleArrowFade = () => {
            if (!arrow) return;
            clearTimeout(hideTimer);
            arrow.classList.add('is-hidden');
            hideTimer = window.setTimeout(() => {
                arrow.classList.remove('is-hidden');
            }, 2600);
        };

        const updateCenterItem = () => {
            if (!items.length) return;

            const containerRect = el.getBoundingClientRect();
            const containerCenter = containerRect.left + containerRect.width / 2;
            let closest = null;
            let closestDist = Infinity;

            items.forEach((item) => {
                const rect = item.getBoundingClientRect();
                const itemCenter = rect.left + rect.width / 2;
                const dist = Math.abs(itemCenter - containerCenter);
                if (dist < closestDist) {
                    closestDist = dist;
                    closest = item;
                }
            });

            items.forEach((item) => {
                item.classList.toggle('is-center', item === closest);
            });
        };

        const requestCenterUpdate = () => {
            if (ticking) return;
            ticking = true;
            requestAnimationFrame(() => {
                updateCenterItem();
                ticking = false;
            });
        };

        if (items.length) {
            const middleItem = items[Math.floor((items.length - 1) / 2)];
            el.scrollLeft = middleItem.offsetLeft + middleItem.offsetWidth / 2 - el.clientWidth / 2;
        }

        updateCenterItem();

        el.addEventListener('scroll', requestCenterUpdate, { passive: true });
        window.addEventListener('resize', requestCenterUpdate);

        el.addEventListener('pointerdown', (event) => {
            isDown = true;
            startX = event.clientX;
            startScroll = el.scrollLeft;
            el.setPointerCapture(event.pointerId);
            scheduleArrowFade();
        });

        el.addEventListener('pointermove', (event) => {
            if (!isDown) return;
            el.scrollLeft = startScroll - (event.clientX - startX);
        });

        ['pointerup', 'pointercancel', 'pointerleave'].forEach((eventName) => {
            el.addEventListener(eventName, () => {
                isDown = false;
            });
        });

        el.addEventListener('wheel', () => {
            scheduleArrowFade();
        }, { passive: true });

        if (arrow) {
            arrow.addEventListener('click', () => {
                const atEnd = el.scrollLeft + el.clientWidth >= el.scrollWidth - 8;
                const step = Math.max(el.clientWidth * 0.6, 220);

                el.scrollTo({
                    left: atEnd ? 0 : el.scrollLeft + step,
                    behavior: 'smooth',
                });

                scheduleArrowFade();
            });
        }
    });
}
