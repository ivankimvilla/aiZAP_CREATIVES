function delegate(selector, eventName, handler) {
    document.addEventListener(eventName, function (event) {
        if (!event.target) {
            return;
        }
        var button = event.target.closest(selector);
        if (!button) {
            return;
        }
        handler.call(button, event);
    });
}

function initLiveValidation() {
    var nameInput = document.getElementById('modal_admin_name');
    var emailInput = document.getElementById('modal_admin_email');
    var createBtn = document.querySelector('#adminModalOverlay form button[type="submit"]');
    var tokenInput = document.querySelector('#adminModalOverlay form input[name="_token"]');
    if (!nameInput || !emailInput) return;

    var debounce = function (fn, wait) {
        var timeout;
        return function () {
            var args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                fn.apply(null, args);
            }, wait);
        };
    };

    var check = function (field, value, cb) {
        var token = tokenInput ? tokenInput.value : '';
        fetch('/admin/admins/check', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ field: field, value: value })
        }).then(function (response) {
            return response.json();
        }).then(function (json) {
            cb(null, json);
        }).catch(function (error) {
            cb(error);
        });
    };

    var nameError = createErrorNode(nameInput);
    var emailError = createErrorNode(emailInput);
    var state = { nameTaken: false, emailTaken: false };

    nameInput.addEventListener('input', debounce(function (event) {
        var value = event.target.value.trim();
        if (!value) {
            nameError.textContent = '';
            nameInput.classList.remove('invalid');
            state.nameTaken = false;
            toggleCreate();
            return;
        }

        check('name', value, function (err, result) {
            if (err) return;
            if (result.exists) {
                nameError.textContent = result.message;
                nameInput.classList.add('invalid');
                state.nameTaken = true;
            } else {
                nameError.textContent = '';
                nameInput.classList.remove('invalid');
                state.nameTaken = false;
            }
            toggleCreate();
        });
    }, 300));

    emailInput.addEventListener('input', debounce(function (event) {
        var value = event.target.value.trim();
        if (!value) {
            emailError.textContent = '';
            emailInput.classList.remove('invalid');
            state.emailTaken = false;
            toggleCreate();
            return;
        }

        check('email', value, function (err, result) {
            if (err) return;
            if (result.exists) {
                emailError.textContent = result.message;
                emailInput.classList.add('invalid');
                state.emailTaken = true;
            } else {
                emailError.textContent = '';
                emailInput.classList.remove('invalid');
                state.emailTaken = false;
            }
            toggleCreate();
        });
    }, 300));

    function toggleCreate() {
        if (createBtn) {
            createBtn.disabled = state.nameTaken || state.emailTaken;
        }
    }

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

function initAdminCreateSubmit() {
    var form = document.querySelector('#adminModalOverlay form');
    if (!form) return;

    form.addEventListener('submit', function (event) {
        var liveErrors = form.querySelectorAll('.live-error');
        for (var i = 0; i < liveErrors.length; i++) {
            if (liveErrors[i].textContent && liveErrors[i].textContent.trim().length > 0) {
                var related = liveErrors[i].previousElementSibling;
                if (related && related.focus) {
                    related.focus();
                }
                event.preventDefault();
                return;
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    delegate('.eye-toggle', 'click', function () {
        var input = document.getElementById(this.dataset.target);
        if (!input) {
            return;
        }

        var eye = this.querySelector('.icon-eye');
        var eyeOff = this.querySelector('.icon-eye-off');
        var isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';
        if (eye) {
            eye.style.display = isHidden ? 'none' : 'block';
        }
        if (eyeOff) {
            eyeOff.style.display = isHidden ? 'block' : 'none';
        }
        this.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
    });

    delegate('.toggle-row-password', 'click', function () {
        var targetId = this.getAttribute('data-target');
        if (!targetId) {
            return;
        }

        var target = document.getElementById(targetId);
        if (!target) {
            return;
        }

        if (typeof target.dataset.real === 'undefined') {
            return;
        }

        var real = target.dataset.real;
        var masked = target.dataset.masked || '••••••••••';
        var isRevealed = target.dataset.visible === 'true';
        target.textContent = isRevealed ? masked : real;
        target.dataset.visible = isRevealed ? 'false' : 'true';

        this.setAttribute('aria-pressed', isRevealed ? 'false' : 'true');
        this.setAttribute('aria-label', isRevealed ? 'Show password' : 'Hide password');

        var eyeIcon = this.querySelector('.icon-eye');
        var eyeOffIcon = this.querySelector('.icon-eye-off');
        if (eyeIcon) {
            eyeIcon.style.display = isRevealed ? 'none' : 'block';
        }
        if (eyeOffIcon) {
            eyeOffIcon.style.display = isRevealed ? 'block' : 'none';
        }
    });

    delegate('.copy-btn', 'click', function () {
        var copyTarget = this.getAttribute('data-copy-target');
        if (!copyTarget) {
            return;
        }

        var source = document.getElementById(copyTarget);
        if (!source) {
            return;
        }

        var textToCopy = source.textContent.trim();
        if (!textToCopy) {
            return;
        }

        var originalAriaLabel = this.getAttribute('aria-label') || 'Copy to Clipboard';
        var copyIcon = this.querySelector('.icon-copy');
        var checkIcon = this.querySelector('.icon-check');

        if (this._copyResetTimer) {
            clearTimeout(this._copyResetTimer);
        }

        var completeCopy = function () {
            if (copyIcon) {
                copyIcon.style.display = 'none';
            }
            if (checkIcon) {
                checkIcon.style.display = 'block';
            }
            this.classList.add('copied');
            this.setAttribute('aria-label', 'Copied');

            this._copyResetTimer = setTimeout(function () {
                if (copyIcon) {
                    copyIcon.style.display = 'block';
                }
                if (checkIcon) {
                    checkIcon.style.display = 'none';
                }
                this.classList.remove('copied');
                this.setAttribute('aria-label', originalAriaLabel);
                delete this._copyResetTimer;
            }.bind(this), 2000);
        }.bind(this);

        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(textToCopy).then(function () {
                completeCopy();
            }).catch(function () {
                fallbackCopy(textToCopy);
            });
        } else {
            fallbackCopy(textToCopy);
        }

        function fallbackCopy(text) {
            var textarea = document.createElement('textarea');
            textarea.value = text;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'absolute';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();
            textarea.setSelectionRange(0, textarea.value.length);
            try {
                document.execCommand('copy');
                completeCopy();
            } catch (err) {
                console.warn('Copy failed', err);
            }
            document.body.removeChild(textarea);
        }
    });

    document.querySelectorAll('[data-menu-toggle]').forEach(function (button) {
        button.addEventListener('click', function (event) {
            event.stopPropagation();
            var menu = button.parentElement.querySelector('.admin-dropdown');
            if (!menu) {
                return;
            }

            document.querySelectorAll('.admin-dropdown.open').forEach(function (openMenu) {
                if (openMenu !== menu) {
                    openMenu.classList.remove('open');
                }
            });

            menu.classList.toggle('open');
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.admin-dropdown.open').forEach(function (menu) {
            menu.classList.remove('open');
        });
    });

    initLiveValidation();
    initAdminCreateSubmit();

    var modalOverlay = document.getElementById('adminModalOverlay');
    var openModalButtons = document.querySelectorAll('.admin-open-modal');
    var closeModalButtons = document.querySelectorAll('[data-close-modal]');

    openModalButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (!modalOverlay) {
                return;
            }

            modalOverlay.classList.add('open');

            modalOverlay.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]').forEach(function (input) {
                input.value = '';
                if (input.type === 'password') {
                    input.type = 'password';
                }
            });

            modalOverlay.querySelectorAll('.eye-toggle').forEach(function (eyeButton) {
                var eyeIcon = eyeButton.querySelector('.icon-eye');
                var eyeOffIcon = eyeButton.querySelector('.icon-eye-off');
                if (eyeIcon) {
                    eyeIcon.style.display = 'block';
                }
                if (eyeOffIcon) {
                    eyeOffIcon.style.display = 'none';
                }
                eyeButton.setAttribute('aria-label', 'Show password');
            });

            var firstInput = modalOverlay.querySelector('input');
            if (firstInput) {
                firstInput.focus();
            }
        });
    });

    closeModalButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (modalOverlay) {
                modalOverlay.classList.remove('open');
            }
        });
    });

    if (modalOverlay) {
        modalOverlay.addEventListener('click', function (event) {
            if (event.target === modalOverlay) {
                modalOverlay.classList.remove('open');
            }
        });
    }

    var changePasswordOverlay = document.getElementById('adminChangePasswordOverlay');
    var changePasswordForm = document.getElementById('adminChangePasswordForm');
    var changePasswordEmail = document.getElementById('adminChangePasswordEmail');
    var changePasswordCloseButtons = changePasswordOverlay ? changePasswordOverlay.querySelectorAll('[data-close-modal]') : [];

    document.querySelectorAll('[data-open-admin-change-password-url]').forEach(function (button) {
        button.addEventListener('click', function () {
            if (!changePasswordOverlay || !changePasswordForm || !changePasswordEmail) {
                return;
            }

            changePasswordForm.action = button.dataset.openAdminChangePasswordUrl;
            changePasswordEmail.textContent = button.dataset.adminEmail || '';
            changePasswordOverlay.classList.add('open');

            changePasswordOverlay.querySelectorAll('input[type="password"]').forEach(function (input) {
                input.type = 'password';
                input.value = '';
            });

            changePasswordOverlay.querySelectorAll('.eye-toggle').forEach(function (eyeButton) {
                var eyeIcon = eyeButton.querySelector('.icon-eye');
                var eyeOffIcon = eyeButton.querySelector('.icon-eye-off');
                if (eyeIcon) {
                    eyeIcon.style.display = 'block';
                }
                if (eyeOffIcon) {
                    eyeOffIcon.style.display = 'none';
                }
                eyeButton.setAttribute('aria-label', 'Show password');
            });

            var firstInput = changePasswordOverlay.querySelector('input');
            if (firstInput) {
                firstInput.focus();
            }
        });
    });

    changePasswordCloseButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            if (changePasswordOverlay) {
                changePasswordOverlay.classList.remove('open');
            }
        });
    });

    if (changePasswordOverlay) {
        changePasswordOverlay.addEventListener('click', function (event) {
            if (event.target === changePasswordOverlay) {
                changePasswordOverlay.classList.remove('open');
            }
        });
    }
});
