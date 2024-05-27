/** @type {import("prettier").Config} */
const config = {
  trailingComma: 'es5',
  tabWidth: 4,
  semi: true,
  singleQuote: true,
  arrowParens: 'avoid',
  printWidth: 80,
  quoteProps: 'as-needed',
  bracketSameLine: true,

  phpVersion: '8.2',
  plugins: ['@prettier/plugin-php'],
};

export default config;
