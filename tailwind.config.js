/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {},
    },
    darkMode: "class",
    plugins: [
        require('flowbite/plugin'),
    ]
};
