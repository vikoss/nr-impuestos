/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    colors: {
      'wine': '#54191b',
      'wine-100': '#691c32',
      'wine-200': '#8e1537',
      'wine-logo': '#73051c',
      'red-error': '#d93025',
      'gray': '#a0a0a0',
      'gray-100': '#d5d6d1',
      'gray-200': '#e0e2e0',
      'gray-300': '#f5f5f5',
      'white': '#ffffff',
      'black': '#333333',
    },
    extend: {},
  },
  plugins: [],
}
