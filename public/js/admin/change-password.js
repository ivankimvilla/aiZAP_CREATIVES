document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.eye-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var input = document.getElementById(btn.dataset.target);
            if (!input) {
                return;
            }

            var eye = btn.querySelector('.icon-eye');
            var eyeOff = btn.querySelector('.icon-eye-off');
            var isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            if (eye) {
                eye.style.display = isHidden ? 'none' : 'block';
            }
            if (eyeOff) {
                eyeOff.style.display = isHidden ? 'block' : 'none';
            }
            btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        });
    });
});
