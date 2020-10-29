const { colors } = require('tailwindcss/defaultTheme');

module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: {
    content: [
      'resources/views/**/**/*.blade.php',
      'resources/views/**/*.blade.php',
      'resources/views/*.blade.php'
    ],
    options: {
        whitelist: [
            "text-success-700",
            "text-warning-700",
            "text-failure-700",
            "text-default-700",
            "text-success-500",
            "text-warning-500",
            "text-failure-500",
            "text-default-500",
            "bg-success-200",
            "bg-warning-200",
            "bg-failure-200",
            "bg-default-200",
          ]
    }
  },
  theme: {
    colors: {
        black: colors.black,
        white: colors.white,
        gray: colors.gray,
        teal: {
            '100': '#D2F9F7',
            '200': '#A6F2F0',
            '300': '#79ECE8',
            '400': '#4CE6E0',
            '500': '#20DFD9',
            '600': '#19AEA9',
            '700': '#127D79',
            '800': '#0B4C4A',
            '900': '#041B1A'
        },
        red: colors.red,
        yellow: colors.yellow,
        green: colors.green,
        blue: colors.blue,
        indigo: colors.indigo,
        purple: colors.purple,
        orange: colors.orange,
        pink: colors.pink,
        success: colors.green,
        warning: colors.yellow,
        failure: colors.red,
        default: colors.blue,
    },
    maxHeight: {
      '120': '30rem'
    },
    extend: {
        spacing:  {
            '120': '30rem'
        }
    },
  },
  variants: {},
  plugins: [
    require('@tailwindcss/typography'),
  ],
}
