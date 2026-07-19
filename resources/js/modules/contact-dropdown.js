export function initContactDropdown() {
    const dropdown = document.getElementById('contactDropdown');
    if (!dropdown || dropdown.dataset.contactBound === '1') return;
    dropdown.dataset.contactBound = '1';

    const component = dropdown.closest('.contact-dropdown-component');
    const contactButton = component?.querySelector('.contact-float-button');
    const closeBtn = dropdown.querySelector('.contact-dropdown-close');

    const setOpen = (open) => {
        dropdown.classList.toggle('open', open);
        dropdown.setAttribute('aria-hidden', open ? 'false' : 'true');
    };

    const toggleDropdown = (event) => {
        event.preventDefault();
        event.stopPropagation();
        setOpen(!dropdown.classList.contains('open'));
    };

    if (contactButton) {
        contactButton.addEventListener('click', toggleDropdown);
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

    if (dropdown.dataset.initialOpen === 'true') {
        setOpen(true);
        const firstInput = dropdown.querySelector('input, textarea');
        if (firstInput) {
            firstInput.focus();
        }
    }
}
