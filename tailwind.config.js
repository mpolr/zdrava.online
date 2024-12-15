/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.js",
        "./resources/**/*.blade.php",
        "./node_modules/flowbite/**/*.js",
        './vendor/masmerise/livewire-toaster/resources/views/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    ],
    theme: {
        extend: {},
    },
    darkMode: "class",
    plugins: [
        require('flowbite/plugin'),
    ]
};
