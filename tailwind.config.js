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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta Campfire - Tons quentes e acolhedores
                campfire: {
                    50: '#fff7ed',   // Laranja muito claro
                    100: '#ffedd5',  // Laranja claro
                    200: '#fed7aa',  // Laranja suave
                    300: '#fdba74',  // Laranja médio
                    400: '#fb923c',  // Laranja vibrante
                    500: '#f97316',  // Laranja principal
                    600: '#ea580c',  // Laranja escuro
                    700: '#c2410c',  // Laranja profundo
                    800: '#9a3412',  // Vermelho-laranja
                    900: '#7c2d12',  // Marrom quente
                },
                ember: {
                    50: '#fef2f2',   // Vermelho muito claro
                    100: '#fee2e2',  // Vermelho claro
                    200: '#fecaca',  // Vermelho suave
                    300: '#fca5a5',  // Vermelho médio
                    400: '#f87171',  // Vermelho vibrante
                    500: '#ef4444',  // Vermelho principal
                    600: '#dc2626',  // Vermelho escuro
                    700: '#b91c1c',  // Vermelho profundo
                    800: '#991b1b',  // Vermelho intenso
                    900: '#7f1d1d',  // Marrom-vermelho
                },
                ash: {
                    50: '#fafafa',   // Cinza muito claro
                    100: '#f5f5f5',  // Cinza claro
                    200: '#e5e5e5',  // Cinza suave
                    300: '#d4d4d4',  // Cinza médio-claro
                    400: '#a3a3a3',  // Cinza médio
                    500: '#737373',  // Cinza principal
                    600: '#525252',  // Cinza escuro
                    700: '#404040',  // Cinza profundo
                    800: '#262626',  // Cinza muito escuro
                    900: '#171717',  // Quase preto
                },
            },
        },
    },

    plugins: [forms],
};
