import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#FFF3ED',
                    100: '#FFE5D9',
                    200: '#FFCCB8',
                    300: '#FFAA8F',
                    400: '#FF7D57',
                    500: '#FF5722', // Blaze Orange (Primary)
                    600: '#E64A19', // Hover
                    700: '#D84315',
                    800: '#BF360C',
                    900: '#872300',
                    primary: '#FF5722',
                    hover: '#E64A19',
                    accent: '#FF6B00',
                    amber: '#FFB703',
                    dark: '#111317',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                inter: ['Inter', ...defaultTheme.fontFamily.sans],
                poppins: ['Poppins', ...defaultTheme.fontFamily.sans],
                heading: ['Montserrat', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
