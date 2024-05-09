/** @type {import('tailwindcss').Config} */
module.exports = {
  corePlugins: {
    preflight: false,
  },
  content: ["./resources/**/*.{html,js,vue,php,blade.php}"],
  important: '.custom',
  theme: {
    extend: {},
  },
  plugins: [require('@tailwindcss/aspect-ratio')],
}
