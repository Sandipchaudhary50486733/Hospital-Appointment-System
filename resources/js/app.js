import Swal from 'sweetalert2';
import '@fortawesome/fontawesome-free/css/all.min.css';

window.Swal = Swal;

/**
 * Reads #flash-data (rendered server-side in layouts) and fires the
 * matching SweetAlert automatically on page load.
 */
document.addEventListener('DOMContentLoaded', () => {
    const flash = document.getElementById('flash-data');
    if (!flash) return;

    const status = flash.dataset.status;
    const error = flash.dataset.error;

    if (status) {
        Swal.fire({
            icon: 'success', title: 'Success', text: status,
            confirmButtonColor: '#0d6efd', timer: 4000, timerProgressBar: true,
        });
    }
    if (error) {
        Swal.fire({
            icon: 'error', title: 'Something needs your attention', text: error,
            confirmButtonColor: '#0d6efd',
        });
    }
});

/**
 * <form data-confirm="Are you sure?"> is intercepted with a SweetAlert
 * confirmation dialog before it actually submits.
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
 * Generic dropdown menu toggle.
 * Any button with data-dropdown-toggle="#menu-id" shows/hides the element
 * with that id. Clicking anywhere outside an open dropdown closes it.
 * Works for any number of independent dropdowns on the same page.
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-dropdown-toggle]').forEach((btn) => {
        const targetSelector = btn.getAttribute('data-dropdown-toggle');
        const menu = document.querySelector(targetSelector);
        if (!menu) return;

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = !menu.classList.contains('hidden');
            // Close any other open dropdowns first.
            document.querySelectorAll('[data-dropdown-menu]').forEach((m) => m.classList.add('hidden'));
            menu.classList.toggle('hidden', isOpen);
        });
    });

    document.addEventListener('click', () => {
        document.querySelectorAll('[data-dropdown-menu]').forEach((m) => m.classList.add('hidden'));
    });

    // Prevent clicks inside the dropdown menu from closing it immediately.
    document.querySelectorAll('[data-dropdown-menu]').forEach((menu) => {
        menu.addEventListener('click', (e) => e.stopPropagation());
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
