/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    colors: {
      'wine': '#660032',
      'wine-100': '#691c32',
      'wine-200': '#8e1537',
      'wine-logo': '#73051c',
      'red-error': '#d93025',
      'gray': '#666666',
      'gray-100': '#999999',
      'gray-200': '#e0e2e0',
      'gray-300': '#f5f5f5',
      'white': '#ffffff',
      'black': '#333333',
    },
    extend: {},
  },
  plugins: [],
}
