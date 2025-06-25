import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                jakarta: ['PlusJakartaSans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'merah1'    : '#B71C1C',
                'merah1muda': '#BA5757',
                'merah2'    : '#E82D1F',
                'oren'      : '#F15A42',
                'kuning1'   : '#F7B750',
                'kuning2'   : '#FEE9A3',
                'biru1'     : '#063051',
                'biru2'     : '#184D77',
                'biru3'     : '#4987A0',
                'biru4'     : '#4C84B0',
                'biru5'     : '#C7D9E5',
                'hijau'     : '#388E3C',
                'hijau2'    : '#90CC93',
                'hijaumuda' : '#5FB463',
                'abubiru'   : '#E3E9ED',
                'putihbg'   : '#F3F8FB',
            }
        },
    },
    plugins: [],
};
