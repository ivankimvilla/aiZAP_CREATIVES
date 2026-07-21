const adminCardToggle = () => {
    if (typeof window !== 'undefined' && window.__adminCardToggleInit) return;
    if (typeof window !== 'undefined') window.__adminCardToggleInit = true;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function isInteractiveElement(el) {
        if (!el || el.nodeType !== 1) return false;
        return !!el.closest('a, button, input, textarea, select, label, form');
    }

    document.querySelectorAll('.msg-card').forEach(function (card) {
        card.addEventListener('click', async function (e) {
            // Normalize to an Element in case a text node was clicked
            let target = e.target;
            if (target && target.nodeType !== 1) target = target.parentElement;
            if (isInteractiveElement(target)) return;

            const wasCollapsed = card.classList.contains('collapsed');

            // Close all other cards (accordion behavior)
            document.querySelectorAll('.msg-card').forEach(function (other) {
                if (other !== card) other.classList.add('collapsed');
            });

            // Toggle the clicked card: expand if it was collapsed, otherwise collapse
            if (wasCollapsed) {
                card.classList.remove('collapsed');
            } else {
                card.classList.add('collapsed');
            }

            // If the card is now expanded and was unseen, mark as read via POST
            const isNowExpanded = !card.classList.contains('collapsed');
            const seen = card.dataset.cardSeen === 'true';
            const markReadUrl = card.dataset.markReadUrl;

            if (isNowExpanded && !seen && markReadUrl) {
                try {
                    await fetch(markReadUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: new URLSearchParams()
                    });

                    card.dataset.cardSeen = 'true';
                    const badge = card.querySelector('.msg-new');
                    if (badge) badge.remove();
                } catch (err) {
                    console.error('Failed to mark card read', err);
                }
            }
        });
    });
};

export default adminCardToggle;
