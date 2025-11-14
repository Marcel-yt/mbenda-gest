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

    plugins: [forms],
};

module.exports = {
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './app/View/Components/**/*.php',
  ],
  safelist: [
    'bg-gray-100','text-gray-700','border-gray-300',
    'bg-green-100','text-green-700','border-green-200',
    'bg-blue-100','text-blue-700','border-blue-200',
    'bg-orange-100','text-orange-700','border-orange-200',
    'bg-yellow-100','text-yellow-700','border-yellow-200',
    'bg-red-100','text-red-700','border-red-200',
    'border','rounded','px-2','py-1','text-xs','font-medium','inline-flex','items-center'
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
