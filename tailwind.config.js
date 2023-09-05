/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./templates/**/*.{twig,html.twig}",
    "./assets/js/**/*.{js,jsx,ts,tsx,vue}",
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        'indigoes': {
          DEFAULT: '#8085E4',
          50: '#DFE0F8',
          100: '#D4D6F6',
          200: '#BFC2F1',
          300: '#AAADED',
          400: '#9599E8',
          500: '#8085E4',
          600: '#676DDF',
          700: '#4E55D9',
          800: '#343CD4',
          900: '#2931C1',
          950: '#262DB4'
        },
      },
      zIndex: {
        1: "1",
        2: "2",
        3: "3",
        4: "4",
        5: "5",
      },
      fontFamily: {
        fontawesome: ['FontAwesome'],
      },
      maxWidth: {
        'screen-fhd': '120rem',
        'screen-qhd': '160rem',
      },
      padding: {
        '26': '6.5rem',
      },
      margin: {
        '26': '6.5rem',
      },
      backdropBlur: {
        xs: '2px',
      },
      borderWidth: {
        '3': '3px',
        '5': '5px',
        '6': '6px',
        '7': '7px',
      },
      fontSize: {
        '8xlc': ['6rem', '4.5rem'],
        '9xlc': ['8rem', '6rem'],
        '10xlc': ['10.625rem', '8rem'],
      }
    },
    fontFamily: {
      sans: ['"Blinker", sans-serif'],
    },
  },
  plugins: [
    require("@tailwindcss/forms"),
  ],
};


