// Validate password match on the registration form
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('registerForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;

            if (pass !== confirm) {
                e.preventDefault();
                alert('Password and confirmation do not match!');
            }

            if (pass.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long');
            }
        });
    }

    // Preview the image before uploading it on the profile page
    const fileInput = document.getElementById('avatarInput');
    const preview = document.getElementById('avatarPreview');
    if (fileInput && preview) {
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                preview.src = URL.createObjectURL(file);
            }
        });
    }
});
