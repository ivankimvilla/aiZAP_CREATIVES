export function initPricingPage() {
    if (!isPricingPage) return;

    const glow = document.querySelector('.pricing-heading-glow');
    if (glow) {
        glow.addEventListener('pointermove', (event) => {
            const rect = glow.getBoundingClientRect();
            glow.style.setProperty('--glow-x', `${event.clientX - rect.left}px`);
            glow.style.setProperty('--glow-y', `${event.clientY - rect.top}px`);
        });
    }

    const cards = document.querySelectorAll('.pricing-card');
    const overlay = document.getElementById('pricingOverlay');
    const pricingPanel = document.getElementById('pricingInquiryPanel');
    if (!cards.length || !overlay || !pricingPanel) return;

    const pricingPlans = (() => {
        try {
            return JSON.parse(overlay.dataset.plans || '{}');
        } catch (error) {
            return {};
        }
    })();

    let selectedCard = null;

    // --- ADDED: was missing, caused a ReferenceError that blocked closeOverlay() ---
    const animateToast = (toast, delay = 3200) => {
        if (!toast) return;
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => toast.remove(), 360);
        }, delay);
    };

    const getSummaryElements = () => {
        return {
            title: pricingPanel.querySelector('.pricing-inquiry-header .section-title'),
            subtitle: pricingPanel.querySelector('.pricing-inquiry-header .summary-subtitle'),
            featuresEl: pricingPanel.querySelector('.summary-features'),
            packageInput: pricingPanel.querySelector('.pricing-inquiry-form input[name="package"]'),
        };
    };

    const updatePricingSummary = (packageValue) => {
        const { title, subtitle, featuresEl, packageInput } = getSummaryElements();
        const meta = pricingPlans[packageValue] || null;

        if (title && packageValue) {
            title.textContent = packageValue;
        }

        if (packageInput) {
            packageInput.value = packageValue;
        }

        if (subtitle) {
            subtitle.textContent = meta ? meta.subtitle : 'Submit your package request and we’ll respond with pricing details.';
        }

        if (featuresEl) {
            featuresEl.innerHTML = '';
            if (meta && Array.isArray(meta.items)) {
                meta.items.forEach((item) => {
                    const li = document.createElement('li');
                    li.textContent = item;
                    featuresEl.appendChild(li);
                });
            } else {
                ['Concepts & AI video production', 'Platform-ready formats', 'Revisions & delivery timeline'].forEach((item) => {
                    const li = document.createElement('li');
                    li.textContent = item;
                    featuresEl.appendChild(li);
                });
            }
        }
    };

    const clearActiveState = () => {
        cards.forEach((card) => card.classList.remove('is-active', 'is-selected'));
        document.querySelectorAll('.pricing-card .pricing-select-button').forEach((button) => {
            button.classList.remove('is-active');
            button.setAttribute('aria-pressed', 'false');
        });
    };

    const openOverlay = () => {
        overlay.hidden = false;
        overlay.classList.add('is-open');
        pricingPanel.classList.add('is-open');
    };

    const closeOverlay = () => {
        overlay.classList.remove('is-open');
        pricingPanel.classList.remove('is-open');
        overlay.hidden = true;
    };

    const activateCard = (card, persistent, withOverlay = false) => {
        if (!card) return;
        clearActiveState();
        card.classList.add('is-active');
        if (persistent) {
            card.classList.add('is-selected');
            selectedCard = card;
        }
        const button = card.querySelector('.pricing-select-button');
        if (button) {
            button.classList.add('is-active');
            button.setAttribute('aria-pressed', 'true');
        }
        const packageValue = card.querySelector('.pricing-select-button')?.dataset.plan || '';
        if (packageValue) {
            updatePricingSummary(packageValue);
        }
        if (withOverlay) {
            openOverlay();
        }
    };

    const openOverlayForPlan = (packageValue, triggerButton) => {
        const button = triggerButton || document.querySelector(`.pricing-select-button[data-plan="${packageValue}"]`);
        const card = button ? button.closest('.pricing-card') : null;
        if (!card || !packageValue) return;

        button.classList.add('is-active');
        button.setAttribute('aria-pressed', 'true');
        activateCard(card, true, true);

        const nameInput = pricingPanel.querySelector('.pricing-inquiry-form input[name="name"]');
        if (nameInput) {
            nameInput.focus();
        }

        if (window.innerWidth < 900) {
            pricingPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    cards.forEach((card) => {
        card.addEventListener('mouseenter', () => activateCard(card, false));
        card.addEventListener('mouseleave', (event) => {
            const relatedCard = event.relatedTarget && event.relatedTarget.closest
                ? event.relatedTarget.closest('.pricing-card')
                : null;
            if (relatedCard) return;
            if (selectedCard) {
                activateCard(selectedCard, true);
            } else {
                clearActiveState();
            }
        });

        const button = card.querySelector('.pricing-select-button');
        if (button) {
            button.addEventListener('click', (event) => {
                event.preventDefault();
                openOverlayForPlan(button.dataset.plan || '', button);
            });
        }
    });

    const closeButton = pricingPanel.querySelector('.pricing-close');
    if (closeButton) {
        closeButton.addEventListener('click', (event) => {
            event.preventDefault();
            closeOverlay();
        });
    }

    const summaryCancel = pricingPanel.querySelector('.pricing-summary-cancel');
    if (summaryCancel) {
        summaryCancel.addEventListener('click', (event) => {
            event.preventDefault();
            clearActiveState();
            const packageInput = pricingPanel.querySelector('.pricing-inquiry-form input[name="package"]');
            if (packageInput) packageInput.value = '';
            closeOverlay();
        });
    }

    overlay.addEventListener('click', (event) => {
        if (event.target === overlay) {
            closeOverlay();
        }
    });

    document.addEventListener('keydown', (event) => {
        if ((event.key === 'Escape' || event.key === 'Esc') && overlay.classList.contains('is-open')) {
            closeOverlay();
        }
    });

    const initialPackage = overlay.dataset.initialPackage || '';
    const serverOpen = overlay.dataset.serverOpen === '1';
    if (initialPackage) {
        openOverlayForPlan(initialPackage);
    } else if (serverOpen) {
        openOverlay();
    }

    // --- FIXED: close first, then animate the toast; hide leftover server-flash banners ---
    const pricingToast = document.getElementById('pricingSuccessToast');
    if (pricingToast) {
        closeOverlay();
        animateToast(pricingToast, 3400);

        document.querySelectorAll('.pricing-form-success:not([id]), .pricing-form-errors:not([id])').forEach((el) => {
            el.hidden = true;
        });
    }
}