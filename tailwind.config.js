/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/tw-elements/dist/js/**/*.js",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {},
    },
    darkMode: "class",
    plugins: [
        require("tw-elements/dist/plugin.cjs"),
        require('flowbite/plugin')
    ]
}
