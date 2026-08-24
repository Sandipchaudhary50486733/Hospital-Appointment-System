import Swal from 'sweetalert2';
import '@fortawesome/fontawesome-free/css/all.min.css';

window.Swal = Swal;

/**
 * Reads #flash-data (rendered server-side in layouts) and fires the
 * matching SweetAlert automatically on page load — success/status,
 * validation errors, and session-flashed errors.
 */
document.addEventListener('DOMContentLoaded', () => {
    const flash = document.getElementById('flash-data');
    if (!flash) return;

    const status = flash.dataset.status;
    const error = flash.dataset.error;

    if (status) {
        Swal.fire({
            icon: 'success',
            title: 'Success',
            text: status,
            confirmButtonColor: '#0d6efd',
            timer: 4000,
            timerProgressBar: true,
        });
    }

    if (error) {
        Swal.fire({
            icon: 'error',
            title: 'Something needs your attention',
            text: error,
            confirmButtonColor: '#0d6efd',
        });
    }
});

/**
 * Attach to any <form data-confirm="Are you sure?"> to intercept submit
 * with a SweetAlert confirmation dialog before it actually posts.
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', function (e) {
            if (form.dataset.confirmed === 'true') return;
            e.preventDefault();

            Swal.fire({
                title: form.dataset.confirm,
                text: form.dataset.confirmText || 'This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: form.dataset.confirmButton || 'Yes, continue',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        });
    });
});

/**
 * Live image preview for file inputs marked data-preview-target="#id"
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('input[type=file][data-preview-target]').forEach((input) => {
        input.addEventListener('change', function () {
            const target = document.querySelector(input.dataset.previewTarget);
            if (!target || !input.files[0]) return;
            target.src = URL.createObjectURL(input.files[0]);
            target.classList.remove('hidden');
        });
    });
});
