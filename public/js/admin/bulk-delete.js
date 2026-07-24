const adminBulkDelete = () => {
    if (typeof window !== 'undefined' && window.__adminBulkDeleteInit) return;
    if (typeof window !== 'undefined') window.__adminBulkDeleteInit = true;

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function setText(element, text) {
        if (element) element.textContent = text;
    }

    document.querySelectorAll('.bulk-toolbar').forEach(function (toolbar) {
        const selectAll = toolbar.querySelector('.bulk-select-all');
        const selectedCountLabel = toolbar.querySelector('.bulk-selected-count');
        const deleteButton = toolbar.querySelector('.btn-delete-selected');
        const itemSelector = toolbar.dataset.bulkItemSelector;
        const emptyStateSelector = toolbar.dataset.emptyStateSelector;
        const emptyState = emptyStateSelector ? document.querySelector(emptyStateSelector) : null;
        const explicitDeleteUrl = deleteButton?.dataset.deleteUrl?.trim();

        if (!selectAll || !deleteButton || !itemSelector) return;

        // Prefer an explicit URL set by the server-rendered template.
        // Only fall back to guessing if nothing was provided.
        const deleteUrl = explicitDeleteUrl || (function () {
            const currentPath = window.location.pathname.replace(/\/+$/, '');
            console.warn(
                `[bulk-delete] No data-delete-url set on .btn-delete-selected; guessing "${currentPath}/bulk-delete". ` +
                `Set data-delete-url explicitly to avoid wrong routes on nested pages.`
            );
            return `${currentPath}/bulk-delete`;
        })();

        function getCheckboxes() {
            return Array.from(document.querySelectorAll(`${itemSelector} .bulk-item-checkbox`));
        }

        function getSelectedCheckboxes() {
            return getCheckboxes().filter((checkbox) => checkbox.checked);
        }

        function updateToolbar() {
            const checkboxes = getCheckboxes();
            const selected = getSelectedCheckboxes();
            const selectedCount = selected.length;
            const allChecked = checkboxes.length > 0 && selectedCount === checkboxes.length;

            selectAll.checked = allChecked;
            selectAll.indeterminate = !allChecked && selectedCount > 0;
            deleteButton.disabled = selectedCount === 0;
            setText(selectedCountLabel, selectedCount > 0 ? `${selectedCount} selected` : '0 selected');
        }

        function removeSelectedItems() {
            getSelectedCheckboxes().forEach((checkbox) => {
                checkbox.closest(itemSelector)?.remove();
            });
            if (emptyState && document.querySelectorAll(itemSelector).length === 0) {
                emptyState.classList.remove('hidden');
            }
            updateToolbar();
        }

        selectAll.addEventListener('change', function () {
            const checked = selectAll.checked;
            getCheckboxes().forEach((checkbox) => { checkbox.checked = checked; });
            updateToolbar();
        });

        // Event delegation: works for checkboxes added after initial load too.
        const container = document.querySelector(itemSelector.split(' ')[0]) || document;
        container.addEventListener('change', function (e) {
            if (e.target.classList?.contains('bulk-item-checkbox')) {
                updateToolbar();
            }
        });

        deleteButton.addEventListener('click', async function () {
            const selected = getSelectedCheckboxes();
            if (selected.length === 0) return;
            if (!window.confirm('Delete selected records? This cannot be undone.')) return;

            const ids = selected.map((checkbox) => checkbox.dataset.itemId).filter(Boolean);
            if (ids.length === 0) return;

            const requestUrl = deleteUrl.startsWith('http')
                ? deleteUrl
                : new URL(deleteUrl.startsWith('/') ? deleteUrl : `/${deleteUrl.replace(/^\/+/, '')}`, window.location.origin).toString();

            deleteButton.disabled = true;

            try {
                const response = await fetch(requestUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken(),
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ ids }),
                });

                let data = null;
                try { data = await response.json(); } catch (_) { /* non-JSON response */ }

                if (!response.ok) {
                    console.error('Bulk delete failed', response.status, data);
                    window.alert(
                        data?.message ||
                        `Delete failed (status ${response.status}). Check the console/network tab for details.`
                    );
                    return;
                }

                // Accept a few common success shapes so a backend naming
                // mismatch doesn't silently "fail" in the UI.
                const succeeded = data == null || data.ok === true || data.success === true;

                if (succeeded) {
                    removeSelectedItems();
                } else {
                    console.error('Bulk delete: server responded 200 but did not indicate success', data);
                    window.alert(data?.message || 'Delete did not complete. Please check server logs.');
                }
            } catch (error) {
                console.error('Bulk delete error', error);
                window.alert('Network error while deleting. Please try again.');
            } finally {
                updateToolbar();
            }
        });

        updateToolbar();
    });
};

export default adminBulkDelete;