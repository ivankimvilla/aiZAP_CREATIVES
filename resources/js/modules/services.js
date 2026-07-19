export function initServicesShowMore() {
    const grid = document.querySelector('.services-grid');
    if (!grid) return;

    const items = Array.from(grid.querySelectorAll('.service-item'));
    const max = 8;
    if (items.length <= max) return;

    items.forEach((it, i) => {
        if (i >= max) it.classList.add('service-item--hidden');
    });

    let btn = document.getElementById('show-more-services');
    let btnWrap = btn ? btn.closest('.services-actions') : null;

    if (!btn) {
        btnWrap = document.createElement('div');
        btnWrap.className = 'services-actions';
        btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline services-show-more';
        btn.textContent = 'Show More';
        btnWrap.appendChild(btn);
        if (grid.parentNode) {
            grid.parentNode.insertBefore(btnWrap, grid.nextSibling);
        }
    } else {
        btn.classList.add('services-show-more');
        btn.type = 'button';
        if (!btnWrap) {
            btnWrap = document.createElement('div');
            btnWrap.className = 'services-actions';
            if (btn.parentNode) {
                btn.parentNode.insertBefore(btnWrap, btn);
                btnWrap.appendChild(btn);
            }
        }
    }

    btn.addEventListener('click', () => {
        const expanded = grid.classList.toggle('services-grid--expanded');
        if (expanded) {
            items.forEach((it) => it.classList.remove('service-item--hidden'));
            btn.textContent = 'Show Less';
        } else {
            items.forEach((it, i) => {
                if (i >= max) it.classList.add('service-item--hidden');
            });
            btn.textContent = 'Show More';
            grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    });
}
