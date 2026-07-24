
function initEyeToggles(scope) {
    scope = scope || document;
    scope.querySelectorAll('.eye-toggle').forEach(function (btn) {
        if (btn.__hasInit) return; btn.__hasInit = true;
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            var eye = btn.querySelector('.icon-eye');
            var eyeOff = btn.querySelector('.icon-eye-off');
            if (!input) return;
            var isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            eye.style.display = isHidden ? 'none' : 'block';
            eyeOff.style.display = isHidden ? 'block' : 'none';
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
    });
}

function initRowToggles(scope) {
    scope = scope || document;
    scope.querySelectorAll('.toggle-row-password').forEach(function (btn) {
        if (btn.__hasInit) return; btn.__hasInit = true;
        btn.addEventListener('click', function () {
            var targetId = btn.dataset.target;
            var target = document.getElementById(targetId);
            if (!target) return;

            var eye = btn.querySelector('.icon-eye');
            var eyeOff = btn.querySelector('.icon-eye-off');
            var isVisible = target.dataset.visible === 'true';
            var masked = target.dataset.masked || '••••••••••';
            var real = target.dataset.real || masked;

            if (isVisible) {
                target.textContent = masked;
                target.dataset.visible = 'false';
                if (eye) { eye.style.display = 'block'; }
                if (eyeOff) { eyeOff.style.display = 'none'; }
                btn.setAttribute('aria-label', 'Show password');
                btn.setAttribute('aria-pressed', 'false');
            } else {
                target.textContent = real;
                target.dataset.visible = 'true';
                if (eye) { eye.style.display = 'none'; }
                if (eyeOff) { eyeOff.style.display = 'block'; }
                btn.setAttribute('aria-label', 'Hide password');
                btn.setAttribute('aria-pressed', 'true');
            }
        });
    });
}

function initCopyButtons(scope) {
    scope = scope || document;
    scope.querySelectorAll('.copy-btn').forEach(function (btn) {
        if (btn.__hasInit) return; btn.__hasInit = true;
        btn.addEventListener('click', function () {
            var targetId = btn.dataset.copyTarget;
            var target = document.getElementById(targetId);
            if (!target) return;

            var text = target.textContent || '';
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text.trim()).then(function () {
                    var check = btn.querySelector('.icon-check');
                    if (check) {
                        check.style.display = 'block';
                        setTimeout(function () { check.style.display = 'none'; }, 900);
                    }
                    btn.classList.add('copied');
                    setTimeout(function () { btn.classList.remove('copied'); }, 900);
                }).catch(function () {
                    fallbackCopy(text);
                });
            } else {
                fallbackCopy(text);
            }

            function fallbackCopy(t) {
                var ta = document.createElement('textarea');
                ta.value = t.trim();
                document.body.appendChild(ta);
                ta.select();
                try { document.execCommand('copy'); } catch (e) { }
                document.body.removeChild(ta);
            }
        });
    });
}

// Live validation (debounced) for admin creation modal
function initLiveValidation() {
    var nameInput = document.getElementById('modal_admin_name');
    var emailInput = document.getElementById('modal_admin_email');
    var createBtn = document.querySelector('#adminModalOverlay form button[type="submit"]');
    var tokenInput = document.querySelector('#adminModalOverlay form input[name="_token"]');
    if (!nameInput || !emailInput) return;

    var debounce = function (fn, wait) {
        var t;
        return function () { var args = arguments; clearTimeout(t); t = setTimeout(function () { fn.apply(null, args); }, wait); };
    };

    var check = function (field, value, cb) {
        var token = tokenInput ? tokenInput.value : '';
        fetch('/admin/admins/check', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify({ field: field, value: value })
        }).then(function (r) { return r.json(); }).then(function (json) { cb(null, json); }).catch(function (err) { cb(err); });
    };

    var nameError = createErrorNode(nameInput);
    var emailError = createErrorNode(emailInput);
    var state = { nameTaken: false, emailTaken: false };

    nameInput.addEventListener('input', debounce(function (e) {
        var v = e.target.value.trim();
        if (!v) { nameError.textContent = ''; state.nameTaken = false; toggleCreate(); return; }
        check('name', v, function (err, res) {
            if (err) return;
            if (res.exists) { nameError.textContent = res.message; state.nameTaken = true; } else { nameError.textContent = ''; state.nameTaken = false; }
            toggleCreate();
        });
    }, 300));

    emailInput.addEventListener('input', debounce(function (e) {
        var v = e.target.value.trim();
        if (!v) { emailError.textContent = ''; state.emailTaken = false; toggleCreate(); return; }
        check('email', v, function (err, res) {
            if (err) return;
            if (res.exists) { emailError.textContent = res.message; state.emailTaken = true; } else { emailError.textContent = ''; state.emailTaken = false; }
            toggleCreate();
        });
    }, 300));

    function toggleCreate() { if (createBtn) createBtn.disabled = state.nameTaken || state.emailTaken; }

    function createErrorNode(input) {
        var node = input.parentNode.querySelector('.live-error');
        if (!node) {
            node = document.createElement('div');
            node.className = 'live-error';
            node.style.color = '#a12622';
            node.style.fontSize = '0.85rem';
            node.style.marginTop = '0.35rem';
            input.parentNode.appendChild(node);
        }
        return node;
    }
}

// Intercept form submission to do AJAX create and update the admin list in-place
function initAdminCreateSubmit() {
    var form = document.querySelector('#adminModalOverlay form');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        // Prevent submit if live validation errors exist; focus first invalid field
        var liveErrors = form.querySelectorAll('.live-error');
        for (var i = 0; i < liveErrors.length; i++) {
            if (liveErrors[i].textContent && liveErrors[i].textContent.trim().length > 0) {
                var related = liveErrors[i].previousElementSibling;
                if (related && related.focus) related.focus();
                return;
            }
        }
        var formData = new FormData(form);
        var token = formData.get('_token');
        var payload = {};
        formData.forEach(function (v, k) { payload[k] = v; });

        fetch(form.action, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'X-Requested-With': 'XMLHttpRequest' },
            body: JSON.stringify(payload)
        }).then(function (r) {
            if (r.status === 422) return r.json().then(function (j) { throw j; });
            return r.json();
        }).then(function (json) {
            // success: add new admin to the top of the list
            var admin = json.admin;
            var password = json.password || '';
            addAdminRow(admin.id, admin.email, password, admin.name);
            // show success toast
            showToast(json.message || 'Admin created successfully.');
            // close modal
            document.getElementById('adminModalOverlay')?.classList.remove('open');
            form.reset();
        }).catch(function (err) {
            // Display validation errors inline below inputs (no toasts for validation)
            if (err && err.errors) {
                // clear existing live errors
                var n = form.querySelectorAll('.live-error');
                n.forEach(function (el) { el.textContent = ''; });

                // map server errors to inputs
                Object.keys(err.errors).forEach(function (key) {
                    var input = form.querySelector('[name="' + key + '"]');
                    var message = Array.isArray(err.errors[key]) ? err.errors[key][0] : err.errors[key];

                    if (input) {
                        var node = input.parentNode.querySelector('.live-error');
                        if (!node) {
                            node = document.createElement('div');
                            node.className = 'live-error';
                            node.style.color = '#a12622';
                            node.style.fontSize = '0.85rem';
                            node.style.marginTop = '0.35rem';
                            input.parentNode.appendChild(node);
                        }
                        node.textContent = message;
                    } else if (key === 'registration') {
                        // attach generic registration errors to the name field when input cannot be inferred
                        var fallbackInput = form.querySelector('[name="name"]');
                        if (fallbackInput) {
                            var node = fallbackInput.parentNode.querySelector('.live-error');
                            if (!node) {
                                node = document.createElement('div');
                                node.className = 'live-error';
                                node.style.color = '#a12622';
                                node.style.fontSize = '0.85rem';
                                node.style.marginTop = '0.35rem';
                                fallbackInput.parentNode.appendChild(node);
                            }
                            node.textContent = message;
                        }
                    }
                });

                // focus first invalid input
                var firstInvalid = form.querySelector('.live-error:not(:empty)');
                if (firstInvalid) {
                    var rel = firstInvalid.previousElementSibling;
                    if (rel && rel.focus) rel.focus();
                }
            } else if (err && err.message) {
                // non-validation errors: show toast
                showToast(err.message, true);
            }
        });
    });
}

function addAdminRow(id, email, password, name) {
    var list = document.querySelector('.admin-list');
    if (!list) return;
    var item = document.createElement('div');
    item.className = 'admin-list-item';

    var emailWrap = document.createElement('div');
    emailWrap.className = 'admin-list-email-wrap';
    var emailSpan = document.createElement('span');
    emailSpan.id = 'admin-email-' + id;
    emailSpan.className = 'admin-list-email';
    emailSpan.textContent = email;
    var emailCopy = document.createElement('button');
    emailCopy.type = 'button';
    emailCopy.className = 'icon-btn copy-btn';
    emailCopy.setAttribute('data-copy-target', emailSpan.id);
    emailCopy.setAttribute('aria-label', 'Copy admin email');
    emailCopy.innerHTML = '<svg class="icon-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg><svg class="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M5 13l4 4L19 7"></path></svg>';
    emailWrap.appendChild(emailSpan);
    emailWrap.appendChild(emailCopy);

    var pwWrap = document.createElement('div');
    pwWrap.className = 'admin-list-password-wrap';
    var pwSpan = document.createElement('span');
    pwSpan.id = 'admin-password-' + id;
    pwSpan.className = 'admin-list-password';
    pwSpan.setAttribute('data-masked', '••••••••••');
    if (password) pwSpan.setAttribute('data-real', password);
    pwSpan.setAttribute('data-visible', 'false');
    pwSpan.textContent = '••••••••••';

    var eyeBtn = document.createElement('button');
    eyeBtn.type = 'button';
    eyeBtn.className = 'icon-btn toggle-row-password';
    eyeBtn.setAttribute('data-target', pwSpan.id);
    eyeBtn.setAttribute('aria-label', 'Show password');
    eyeBtn.setAttribute('aria-pressed', 'false');
    eyeBtn.innerHTML = '<svg class="icon-eye" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z"></path><circle cx="12" cy="12" r="3"></circle></svg><svg class="icon-eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-7-11-7a20.9 20.9 0 0 1 5.06-6.06M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 7 11 7a20.9 20.9 0 0 1-4.22 5.5M14.12 14.12a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';

    var pwCopy = document.createElement('button');
    pwCopy.type = 'button';
    pwCopy.className = 'icon-btn copy-btn';
    pwCopy.setAttribute('data-copy-target', pwSpan.id);
    pwCopy.setAttribute('aria-label', 'Copy password');
    pwCopy.innerHTML = '<svg class="icon-copy" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg><svg class="icon-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M5 13l4 4L19 7"></path></svg>';

    pwWrap.appendChild(pwSpan);
    pwWrap.appendChild(eyeBtn);
    pwWrap.appendChild(pwCopy);

    item.appendChild(emailWrap);
    item.appendChild(pwWrap);

    // menu placeholder
    var menu = document.createElement('div');
    menu.className = 'admin-list-menu';
    item.appendChild(menu);

    list.insertBefore(item, list.firstChild);

    // update pill count
    var pill = document.querySelector('.admin-list-pill');
    if (pill) {
        var match = pill.textContent.match(/(\d+)/);
        if (match) {
            var n = parseInt(match[1], 10) + 1;
            pill.textContent = n + (n === 1 ? ' admin' : ' admins');
        }
    }

    // initialize handlers on the new nodes
    initRowToggles(item);
    initCopyButtons(item);
}

function showToast(message, isError) {
    var container = document.querySelector('.admin-list-card');
    if (!container) return;
    var alert = document.createElement('div');
    alert.className = 'alert ' + (isError ? 'alert-error' : 'alert-success');
    alert.innerHTML = '<span class="alert-icon">' + (isError ? '&#33;' : '&#10003;') + '</span>' + message;
    container.insertBefore(alert, container.firstChild);
    setTimeout(function () { alert.remove(); }, 3500);
}

// Initialize everything on load
document.addEventListener('DOMContentLoaded', function () {
    initEyeToggles();
    initRowToggles();
    initCopyButtons();
    initLiveValidation();
    initAdminCreateSubmit();
});
