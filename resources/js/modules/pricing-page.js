export function initPricingPage() {
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

    const getPlanMeta = (packageValue) => pricingPlans[packageValue] || null;

    const clearActiveState = () => {
        cards.forEach((card) => {
            card.classList.remove('is-active', 'is-selected');
        });

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

    const updatePricingSummary = (packageValue) => {
        const title = pricingPanel.querySelector('.pricing-inquiry-header .section-title');
        const subtitle = pricingPanel.querySelector('.pricing-inquiry-header .summary-subtitle');
        const featuresEl = pricingPanel.querySelector('.summary-features');
        const packageInput = pricingPanel.querySelector('.pricing-inquiry-form input[name="package"]');

        if (title && packageValue) {
            title.textContent = packageValue;
        }

        if (packageInput) {
            packageInput.value = packageValue;
        }

        const meta = getPlanMeta(packageValue);
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

        const packageValue = button?.dataset.plan || '';
        if (packageValue) {
            updatePricingSummary(packageValue);
        }

        if (withOverlay) {
            openOverlay();
        }
    };

    const openOverlayForPlan = (packageValue, triggerButton) => {
        const matchingButton = triggerButton || document.querySelector(`.pricing-select-button[data-plan="${packageValue}"]`);
        const matchingCard = matchingButton ? matchingButton.closest('.pricing-card') : null;

        if (!packageValue || !matchingCard) return;

        activateCard(matchingCard, true);
        if (matchingButton) {
            matchingButton.classList.add('is-active');
            matchingButton.setAttribute('aria-pressed', 'true');
        }

        updatePricingSummary(packageValue);
        openOverlay();

        const nameInput = pricingPanel.querySelector('.pricing-inquiry-form input[name="name"]');
        if (nameInput) {
            nameInput.focus();
        }

        if (window.innerWidth < 900) {
            pricingPanel.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    };

    const restoreSelectedPackage = () => {
        const initialPackage = overlay.dataset.initialPackage || '';
        const serverOpen = overlay.dataset.serverOpen === '1';

        if (initialPackage) {
            openOverlayForPlan(initialPackage);
        }

        if (serverOpen && overlay.hidden) {
            openOverlay();
        }
    };

    cards.forEach((card) => {
        card.addEventListener('mouseenter', () => activateCard(card, false));

        card.addEventListener('mouseleave', (event) => {
            const relatedCard = event.relatedTarget && event.relatedTarget.closest
                ? event.relatedTarget.closest('.pricing-card')
                : null;

            if (relatedCard) {
                return;
            }

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

    const pricingForm = document.getElementById('pricingInquiryForm');
    const ajaxSuccess = document.getElementById('pricingAjaxSuccess');
    const ajaxErrors = document.getElementById('pricingAjaxErrors');
    const ajaxErrorsList = document.getElementById('pricingAjaxErrorsList');
    const submitBtn = pricingForm?.querySelector('button[type="submit"]');

    const clearFormMessages = () => {
        if (ajaxSuccess) {
            ajaxSuccess.hidden = true;
            ajaxSuccess.textContent = '';
        }
        if (ajaxErrors) {
            ajaxErrors.hidden = true;
        }
        if (ajaxErrorsList) {
            ajaxErrorsList.innerHTML = '';
        }
    };

    const showFormErrors = (messages) => {
        if (!ajaxErrors || !ajaxErrorsList) return;
        messages.forEach((message) => {
            const li = document.createElement('li');
            li.textContent = message;
            ajaxErrorsList.appendChild(li);
        });
        ajaxErrors.hidden = false;
    };

    const ensurePackageSelected = () => {
        const packageInput = pricingForm?.querySelector('input[name="package"]');
        if (!packageInput || !packageInput.value) {
            const err = document.querySelector('.pricing-no-package-error');
            if (!err) {
                const message = document.createElement('div');
                message.className = 'pricing-no-package-error';
                message.style.color = '#ffc9c9';
                message.style.marginBottom = '12px';
                message.textContent = 'Please select a package before requesting pricing.';
                const content = document.querySelector('.pricing-inquiry-content');
                if (content) content.insertBefore(message, content.firstChild);
            }
            packageInput?.focus();
            return false;
        }

        return true;
    };

    if (pricingForm) {
        pricingForm.addEventListener('submit', (event) => {
            event.preventDefault();
            clearFormMessages();

            if (!ensurePackageSelected()) return;

            const formData = new FormData(pricingForm);
            const tokenInput = pricingForm.querySelector('input[name="_token"]');
            const token = tokenInput ? tokenInput.value : '';

            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.dataset.originalText = submitBtn.dataset.originalText || submitBtn.textContent;
                submitBtn.textContent = 'Sending...';
            }

            fetch(pricingForm.action, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token,
                },
            })
                .then(async (response) => {
                    if (response.status === 422) {
                        const data = await response.json().catch(() => null);
                        const messages = data && data.errors
                            ? Object.values(data.errors).flat()
                            : ['Please check the form and try again.'];
                        showFormErrors(messages);
                        return;
                    }

                    if (!response.ok) {
                        showFormErrors(['Something went wrong. Please try again.']);
                        return;
                    }

                    let message = 'Thanks! We received your request and will be in touch shortly.';
                    const contentType = response.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        const data = await response.json().catch(() => null);
                        if (data && data.message) {
                            message = data.message;
                        }
                    }

                    if (ajaxSuccess) {
                        ajaxSuccess.textContent = message;
                        ajaxSuccess.hidden = false;
                    }

                    const packageInput = pricingForm.querySelector('input[name="package"]');
                    const currentPackage = packageInput ? packageInput.value : '';
                    pricingForm.reset();
                    if (packageInput) packageInput.value = currentPackage;
                })
                .catch(() => {
                    showFormErrors(['Network error. Please check your connection and try again.']);
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = submitBtn.dataset.originalText;
                    }
                });
        });
    }

    const closeBtn = pricingPanel.querySelector('.pricing-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', (event) => {
            event.preventDefault();
            closeOverlay();
        });
    }

    const summaryCancel = pricingPanel.querySelector('.pricing-summary-cancel');
    if (summaryCancel) {
        summaryCancel.addEventListener('click', (event) => {
            event.preventDefault();
            clearActiveState();
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

    const successToast = document.getElementById('pricingSuccessToast');
    if (successToast) {
        successToast.classList.add('show');
        setTimeout(() => {
            successToast.classList.remove('show');
        }, 5000);
    }

    restoreSelectedPackage();
}
