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
        },
    },

    safelist: [
        {
            pattern: /bg-(red|green|blue|yellow|purple|pink)-(100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /text-(red|green|blue|yellow|purple|pink)-(100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /border-(red|green|blue|yellow|purple|pink)-(100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /bg-(white|black|gray)-(100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /text-(white|black|gray)-(100|200|300|400|500|600|700|800|900)/,
        },
        {
            pattern: /bg-(transparent|current)/,
        },
        {
            pattern: /text-(transparent|current)/,
        },
        {
            pattern: /border-(transparent|current)/,
        },
        // flex items center justify center grid-cols-1 grid-cols-2 grid-cols-3 grid-cols-4
        {
            pattern: /flex|items-center|justify|grid-cols-\d+/,
        },
        // gap-1 gap-2 gap-3 gap-4 gap-5 gap-6 gap-7 gap-8 gap-9 gap-10
        { pattern: /gap-\d+/ },
        // p-1 p-2 p-3 p-4 p-5 p-6 p-7 p-8 p-9 p-10
        { pattern: /p-\d+/ },
        // m-1 m-2 m-3 m-4 m-5 m-6 m-7 m-8 m-9 m-10
        { pattern: /m-\d+/ },
        // w-1 w-2 w-3 w-4 w-5 w-6 w-7 w-8 w-9 w-10
        { pattern: /w-\d+/ },
        // h-1 h-2 h-3 h-   4 h-5 h-6 h-7 h-8 h-9 h-10
        { pattern: /h-\d+/ },
        // rounded-sm rounded-md rounded-lg rounded-xl rounded-2xl rounded-3xl
        { pattern: /rounded-(sm|md|lg|xl|2xl|3xl)/ },
        // shadow-sm shadow-md shadow-lg shadow-xl shadow-2xl
        { pattern: /shadow-(sm|md|lg|xl|2xl)/ },
        // opacity-0 opacity-25 opacity-50 opacity-75 opacity-100
        { pattern: /opacity-(0|25|50|75|100)/ },
        // transition duration-100 duration-200 duration-300 duration-500 duration-700 duration-1000
        { pattern: /transition|duration-(100|200|300|500|700|1000)/ },
        // transform scale-50 scale-75 scale-90 scale
        { pattern: /transform|scale-(50|75|90|100|110|125|150)/ },
        // hover:scale-50 hover:scale-75 hover:scale-90 hover:
        // { pattern: /hover:scale-(50|75|90|100|110|125|150)/ },
        // focus:scale-50 focus:scale-75 focus:scale-90 focus:
        // { pattern: /focus:scale-(50|75|90|100|110|125|150)/ },
        // hover:transition hover:duration-100 hover:duration-200
        // { pattern: /hover:transition|hover:duration-(100|200|300|500|700|1000)/ },
        // focus:transition focus:duration-100 focus:duration-200
        // { pattern: /focus:transition|focus:duration-(100|200|300|500|700|1000)/ },
        // items-center justify-center
        { pattern: /items-center|justify-center/ },
        { pattern: /text-(center|left|right)/ },

        //line-through
        { pattern: /line-through|p-(x|y|b|t|l|r)|m-(x|y|b|t|l|r)/ },
        'p', 'm', 'mt', 'mb', 'ml', 'mr',
        ...['p', 'm', 'px', 'py', 'pt', 'pb', 'pl', 'pr', 'mx', 'my', 'mt', 'mb', 'ml', 'mr']
            .flatMap(prefix => Array.from({ length: 9 }, (_, i) => `${prefix}-${i}`))
    ],

    plugins: [forms],
};
