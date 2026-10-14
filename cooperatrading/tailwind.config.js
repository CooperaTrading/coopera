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
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                coopera: {
                    navy: '#1D2855',
                    'navy-light': '#2A3567',
                    'navy-dark': '#151D40',
                    'navy-deep': '#313B64',
                    slate: '#1E293B',
                    silver: '#AEADB2',
                    amber: '#F59E0B',
                    'amber-dark': '#B45309',
                    'amber-deep': '#92400E',
                    cream: '#FAF7F2',
                    'cream-warm': '#F3ECE0',
                    'cream-card': '#FFFFFF',
                },
            },
        },
    },

    plugins: [forms],
};
