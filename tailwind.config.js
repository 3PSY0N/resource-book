/** @type {import('tailwindcss').Config} */
import tailwindForms from '@tailwindcss/forms'

export default {
	content: [
		'./templates/**/*.{twig,html.twig}',
		'./assets/js/**/*.{js,jsx,ts,tsx,vue}'
	],
	darkMode: 'class',
	theme: {
		extend: {
			colors: {},
			zIndex: {
				1: '1',
				2: '2',
				3: '3',
				4: '4',
				5: '5',
				500: '500'
			},
			fontFamily: {
				fontawesome: ['FontAwesome']
			},
			maxWidth: {
				'screen-fhd': '120rem',
				'screen-qhd': '160rem'
			},
			padding: {
				'26': '6.5rem'
			},
			margin: {
				'26': '6.5rem'
			},
			backdropBlur: {
				xs: '2px'
			},
			borderWidth: {
				'3': '3px',
				'5': '5px',
				'6': '6px',
				'7': '7px'
			},
			fontSize: {
				'8xlc': ['6rem', '4.5rem'],
				'9xlc': ['8rem', '6rem'],
				'10xlc': ['10.625rem', '8rem']
			},
			animation: {
				'progress': 'progress 10s ease-in 1 forwards;'
			},
			keyframes: {
				'progress': {
					'0%': {
						width: '0'
					},
					'100%': {
						width: '100%'
					}
				}
			}
		},
		fontFamily: {
			sans: ['"Blinker", sans-serif']
		}
	},
	plugins: [
		tailwindForms
	]
}
