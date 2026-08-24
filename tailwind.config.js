/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50:  '#eef4ff',
                    100: '#dbe9ff',
                    500: '#0d6efd',
                    600: '#0b5fd6',
                    700: '#174ea6',
                },
            },
            fontFamily: {
                sans: ['Segoe UI', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
