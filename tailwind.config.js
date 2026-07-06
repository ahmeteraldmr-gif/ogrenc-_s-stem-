/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'primary': {
          50: '#F5F3EF',
          100: '#E8E4DC',
          200: '#D4CCC0',
          300: '#C0B4A4',
          400: '#AC9C88',
          500: '#98846C',
        },
        'secondary': {
          50: '#F8F9FA',
          100: '#E9ECEF',
          200: '#DEE2E6',
          300: '#CED4DA',
          400: '#ADB5BD',
          500: '#6C757D',
        },
        'accent': {
          'blue': '#7C9CBF',
          'green': '#8FA998',
        },
      },
    },
  },
  plugins: [],
}

