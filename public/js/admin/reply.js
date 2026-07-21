const adminReply = () => {
    if (typeof window !== 'undefined' && window.__adminReplyInit) return;
    if (typeof window !== 'undefined') window.__adminReplyInit = true;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function formatNow() {
        const d = new Date();
        const opts = { month: 'short', day: 'numeric' };
        const date = d.toLocaleDateString(undefined, opts);
        let hours = d.getHours();
        const minutes = String(d.getMinutes()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12 || 12;
        return `${date} · ${hours}:${minutes} ${ampm}`;
    }

    document.querySelectorAll('form.msg-reply-form').forEach(function (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            const submitBtn = form.querySelector('button[type="submit"], .btn-primary');
            const textarea = form.querySelector('textarea[name="reply_message"]');
            if (!textarea) return;
            const message = textarea.value.trim();
            if (!message) return;

            submitBtn?.setAttribute('disabled', 'disabled');

            try {
                const formData = new FormData(form);

                const response = await fetch(form.action, {
                    method: form.method || 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                });

                if (!response.ok) {
                    console.error('Reply failed', response.status);
                    submitBtn?.removeAttribute('disabled');
                    return;
                }

                const card = form.closest('.msg-card');
                const chatHistory = card?.querySelector('.chat-history') || form.querySelector('.chat-history');
                if (chatHistory) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'chat-message admin';
                    wrapper.innerHTML = `\n                        <div class="chat-meta">\n                            <span>Admin</span>\n                            <span>·</span>\n                            <span>${formatNow()}</span>\n                        </div>\n                        <div class="chat-row">\n                            <div class="chat-bubble chat-bubble-admin"></div>\n                        </div>\n                    `;
                    wrapper.querySelector('.chat-bubble-admin').textContent = message;
                    chatHistory.appendChild(wrapper);
                    chatHistory.scrollTop = chatHistory.scrollHeight;
                }

                textarea.value = '';
                submitBtn?.removeAttribute('disabled');
            } catch (err) {
                console.error('Reply send error', err);
                submitBtn?.removeAttribute('disabled');
            }
        });
    });
};

export default adminReply;
