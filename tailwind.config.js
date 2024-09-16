import preset from './vendor/filament/support/tailwind.config.preset'
import forms from '@tailwindcss/forms'
import typography from '@tailwindcss/typography'
import colors from 'tailwindcss/colors'

module.exports = {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',

        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php'
    ],
    theme: {
        darkMode: 'class',
        extend: {
            colors: {
                primary: {

                    50: "#D1FFF4",
                    100: "#8FFFE3",
                    200: "#00F0B4",
                    300: "#00D6A1",
                    400: "#00B386",
                    500: "#008060",
                    600: "#007558",
                    700: "#00664D",
                    800: "#005741",
                    900: "#00382A",
                    950: "#002E22"

                },
                secondary: {
                    '50': '#f5faeb',
                    '100': '#eaf3d4',
                    '200': '#d6e8ae',
                    '300': '#bad77f',
                    '400': '#95bf47',
                    '500': '#80a939',
                    '600': '#638729',
                    '700': '#4c6724',
                    '800': '#3e5321',
                    '900': '#364720',
                    '950': '#1b260d',
                },
                main: {
                    DEFAULT: '#F4B41A',
                },
                lightMain: {
                    DEFAULT: '#fff2d3',
                },
                mainDark: {
                    DEFAULT: '#e8a605',
                },
                custom: {
                    DEFAULT: 'rgba(255,190,63,0.47)',
                },
                textColor: {
                    DEFAULT: '#1F3E7C',
                },
                danger: colors.rose,
                success: colors.green,
                warning: colors.yellow,
                gray: colors.gray,
            },
        },
    }, plugins: [forms, typography],
}
