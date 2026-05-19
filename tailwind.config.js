import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                emerald: {
                    50: '#ecfeff',
                    100: '#cffafe',
                    200: '#a5f3fc',
                    300: '#67e8f9',
                    400: '#22d3ee',
                    500: '#06b6d4',
                    600: '#0891b2',
                    700: '#0e7490',
                    800: '#155e75',
                    900: '#164e63',
                    950: '#083344',
                },
                background: {
                    DEFAULT: '#F8FBFF',
                    dark: '#0F172A',
                },
                surface: {
                    DEFAULT: '#FFFFFF',
                    dark: '#1E293B',
                },
                'surface-container': {
                    DEFAULT: '#F0F4F8',
                    dark: '#334155',
                },
                'surface-container-high': {
                    DEFAULT: '#E8EDF2',
                    dark: '#475569',
                },
                'outline-variant': {
                    DEFAULT: '#CFD8DC',
                    dark: '#475569',
                },
                'on-surface': {
                    DEFAULT: '#1A1A2E',
                    dark: '#F1F5F9',
                },
                'on-surface-variant': {
                    DEFAULT: '#546E7A',
                    dark: '#94A3B8',
                },
                'primary': {
                    DEFAULT: '#0891b2',
                    container: '#06b6d4',
                    fixed: '#cffafe',
                },
                'secondary': {
                    DEFAULT: '#0ea5e9',
                    container: '#bae6fd',
                },
                'error': {
                    DEFAULT: '#DC2626',
                    dark: '#EF4444',
                },
            },
            spacing: {
                'container-max': '1200px',
                lg: '20px',
                xl: '24px',
                gutter: '20px',
            },
            fontSize: {
                'display-lg': ['28px', { lineHeight: '36px', letterSpacing: '-0.02em', fontWeight: '700' }],
                'headline-md': ['18px', { lineHeight: '26px', letterSpacing: '-0.01em', fontWeight: '600' }],
                'body-md': ['14px', { lineHeight: '21px', fontWeight: '400' }],
                'label-md': ['13px', { lineHeight: '18px', letterSpacing: '0.01em', fontWeight: '500' }],
                'label-sm': ['11px', { lineHeight: '16px', fontWeight: '500' }],
                'label-xs': ['10px', { lineHeight: '14px', fontWeight: '500' }],
            },
            maxWidth: {
                'container-max': '1200px',
            },
        },
    },

    plugins: [forms],
};
