export function initContactDropdown() {
    const dropdown = document.getElementById('contactDropdown');
    if (!dropdown || dropdown.dataset.contactBound === '1') return;
    dropdown.dataset.contactBound = '1';

    const contactButtons = document.querySelectorAll('.contact-toggle');
    const closeBtn = dropdown.querySelector('.contact-dropdown-close');

    const setOpen = (open) => {
        dropdown.classList.toggle('open', open);
        dropdown.setAttribute('aria-hidden', open ? 'false' : 'true');
    };

    const showToast = (message) => {
        const toast = document.createElement('div');
        toast.className = 'contact-toast';
        toast.setAttribute('role', 'status');
        toast.innerHTML = `
            <div class="contact-toast-content">
                <span class="contact-toast-icon">&#10003;</span>
                <div>${message}</div>
            </div>
        `;
        document.body.appendChild(toast);
        requestAnimationFrame(() => toast.classList.add('show'));
        setTimeout(() => {
            toast.classList.add('fade-out');
            setTimeout(() => {
                toast.remove();
            }, 360);
        }, 3200);
    };

    const toggleDropdown = (event) => {
        event.preventDefault();
        event.stopPropagation();
        setOpen(!dropdown.classList.contains('open'));
    };

    if (contactButtons.length) {
        contactButtons.forEach((button) => {
            button.addEventListener('click', toggleDropdown);
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            setOpen(false);
        });
    }

    document.addEventListener('click', (event) => {
        if (!dropdown.classList.contains('open')) return;
        if (event.target.closest('.contact-dropdown') || event.target.closest('.contact-float-button')) return;
        setOpen(false);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && dropdown.classList.contains('open')) {
            setOpen(false);
        }
    });

    const statusEl = dropdown.querySelector('#contactDropdownStatus');
    const statusMessage = statusEl ? statusEl.textContent.trim() : '';

    if (statusMessage) {
        statusEl.classList.add('fade-out');
        setOpen(false);
        showToast(statusMessage);
    } else if (dropdown.dataset.initialOpen === 'true') {
        setOpen(true);
        const firstInput = dropdown.querySelector('input, textarea');
        if (firstInput) firstInput.focus();
    }
}
