module.exports = {
  future: {
    // removeDeprecatedGapUtilities: true,
    // purgeLayersByDefault: true,
  },
  purge: {
    // content: [
      // 'resources/views/**/**/*.blade.php',
      // 'resources/views/**/*.blade.php',
      // 'resources/views/*.blade.php'
    // ]
  },
  theme: {
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
