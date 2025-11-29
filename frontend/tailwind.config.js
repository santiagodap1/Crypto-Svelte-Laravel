/** @type {import('tailwindcss').Config} */
export default {
    content: ['./src/**/*.{html,js,svelte,ts}'],
    theme: {
        extend: {
            colors: {
                primary: '#22d3ee',
                secondary: '#f97316',
                accent: '#0ea5e9',
                dark: '#05070f',
                'dark-lighter': '#0b1024',
            },
            fontFamily: {
                sans: ['"Space Grotesk"', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [],
}
