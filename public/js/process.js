document.addEventListener('DOMContentLoaded', () => {
    const track = document.querySelector('.process-scroll');
    const leftArrow = document.querySelector('.process-scroll-arrow.left');
    const rightArrow = document.querySelector('.process-scroll-arrow.right');
    if (!track) return;

    const originalItems = Array.from(track.children);
    const n = originalItems.length;
    if (n === 0) return;

    // ---- 1. Build an endless loop: [clone set][real Blade items][clone set] ----
    const setCount = 3;
    const beforeClones = originalItems.map((el) => el.cloneNode(true));
    const afterClones = originalItems.map((el) => el.cloneNode(true));

    track.innerHTML = '';
    const allEls = [...beforeClones, ...originalItems, ...afterClones];
    allEls.forEach((el) => track.appendChild(el));

    function itemBox(el) {
        return { left: el.offsetLeft, width: el.offsetWidth };
    }

    function centerOn(el, smooth = true) {
        const box = itemBox(el);
        const target = box.left - (track.clientWidth / 2 - box.width / 2);
        track.scrollTo({ left: target, behavior: smooth ? 'smooth' : 'auto' });
    }

    // Start centered on the first item of the middle (real) set.
    centerOn(allEls[n], false);

    // ---- 2. Track which item is nearest the viewport center ----
    let currentCenterEl = null;
    function updateCenterClass() {
        const trackRect = track.getBoundingClientRect();
        const viewportCenter = trackRect.left + trackRect.width / 2;
        let closest = null;
        let closestDist = Infinity;
        allEls.forEach((el) => {
            const r = el.getBoundingClientRect();
            const elCenter = r.left + r.width / 2;
            const dist = Math.abs(elCenter - viewportCenter);
            if (dist < closestDist) {
                closestDist = dist;
                closest = el;
            }
        });
        if (closest && closest !== currentCenterEl) {
            allEls.forEach((el) => {
                if (el !== closest) el.classList.remove('is-center');
            });
            closest.classList.add('is-center');
            currentCenterEl = closest;
        }
    }
    updateCenterClass();

    // ---- 3. Only correct the infinite-loop wrap once motion has fully stopped ----
    let isSettling = false;
    function whenMotionStops(cb) {
        if (isSettling) return;
        isSettling = true;
        let lastLeft = track.scrollLeft;
        let stableTicks = 0;
        const check = () => {
            if (track.scrollLeft === lastLeft) {
                stableTicks++;
            } else {
                stableTicks = 0;
                lastLeft = track.scrollLeft;
            }
            if (stableTicks >= 4) {
                isSettling = false;
                cb();
                return;
            }
            requestAnimationFrame(check);
        };
        requestAnimationFrame(check);
    }

    // ---- Drift-proof loop correction: identity-based, not pixel-math based.
    // Finds which real item (0..n-1) is centered, and snaps instantly to its
    // copy in the middle set if it isn't already there. Never adds/subtracts
    // scrollLeft by a computed amount, so there's nothing to accumulate error
    // over repeated swipes and walk you into the true edge of the DOM. ----
    function handleScrollEnd() {
        if (isDown) return; // never correct mid-drag
        if (!currentCenterEl) return;

        const idx = allEls.indexOf(currentCenterEl);
        const realIdx = ((idx % n) + n) % n; // which of the n real items (0..n-1)
        const middleEl = allEls[n + realIdx]; // its copy in the middle (real) set

        if (middleEl !== currentCenterEl) {
            centerOn(middleEl, false); // instant, invisible — same visuals
            allEls.forEach((el) => {
                if (el !== middleEl) el.classList.remove('is-center');
            });
            middleEl.classList.add('is-center');
            currentCenterEl = middleEl;
        }
    }

    function onScroll() {
        updateCenterClass();
        if (isDown) return; // don't attempt to settle/correct mid-drag
        whenMotionStops(handleScrollEnd);
    }
    track.addEventListener('scroll', onScroll, { passive: true });

    // ---- 4. Click any frame (blurred left/right ones too) to bring it to center ----
    let dragMoved = false;
    allEls.forEach((el) => {
        el.style.cursor = 'pointer';
        el.addEventListener('click', () => {
            if (dragMoved) return;
            centerOn(el, true);
        });
    });

    // ---- 5. Mouse drag-to-scroll ----
    let isDown = false;
    let startX = 0;
    let startScroll = 0;

    track.addEventListener('mousedown', (e) => {
        isDown = true;
        dragMoved = false;
        track.classList.add('is-dragging');
        startX = e.pageX;
        startScroll = track.scrollLeft;
    });

    window.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        const dx = e.pageX - startX;
        if (Math.abs(dx) > 4) dragMoved = true;
        track.scrollLeft = startScroll - dx;
    });

    window.addEventListener('mouseup', () => {
        if (!isDown) return;
        isDown = false;
        track.classList.remove('is-dragging');
        whenMotionStops(handleScrollEnd);
        setTimeout(() => {
            dragMoved = false;
        }, 0);
    });

    // ---- 6. Touch swipe (mobile) ----
    track.addEventListener(
        'touchstart',
        (e) => {
            isDown = true;
            dragMoved = false;
            startX = e.touches[0].pageX;
            startScroll = track.scrollLeft;
        },
        { passive: true }
    );

    track.addEventListener(
        'touchmove',
        (e) => {
            if (!isDown) return;
            const dx = e.touches[0].pageX - startX;
            if (Math.abs(dx) > 4) dragMoved = true;
            track.scrollLeft = startScroll - dx;
        },
        { passive: true }
    );

    track.addEventListener('touchend', () => {
        isDown = false;
        whenMotionStops(handleScrollEnd);
    });

    // ---- 7. Left/right arrow buttons step one frame at a time ----
    function step(dir) {
        if (!currentCenterEl) return;
        const idx = allEls.indexOf(currentCenterEl);
        const target = allEls[idx + dir];
        if (target) centerOn(target, true);
    }
    if (leftArrow) leftArrow.addEventListener('click', () => step(-1));
    if (rightArrow) rightArrow.addEventListener('click', () => step(1));

    // ---- 8. Keep centering correct on resize ----
    window.addEventListener('resize', () => {
        if (currentCenterEl) centerOn(currentCenterEl, false);
    });
});