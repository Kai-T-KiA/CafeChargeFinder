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
            height: {
                '400': '400px',
            },
            width: {
                '80p': '80%',
            },
            margin: {
                '10': '10px', // カスタムマージンの追加
            },
        },
        screens: {
            'sm': '640px',
            // 他のスクリーンサイズ設定がある場合
        },
    },

    plugins: [forms],
};
