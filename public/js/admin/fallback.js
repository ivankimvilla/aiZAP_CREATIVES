// Lightweight admin fallback script.
// Present to satisfy the shared admin layout without breaking the page.
document.addEventListener('DOMContentLoaded', function () {
    if (window.console && typeof window.console.debug === 'function') {
        window.console.debug('Admin fallback script loaded');
    }
});
