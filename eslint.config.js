// @ts-check

import eslint from '@eslint/js';
import tseslint from 'typescript-eslint';
import eslintConfigPrettier from 'eslint-config-prettier';

export default tseslint.config({
  files: ['resources/js/**/*.{js,ts,jsx,tsx,html,vue}'],
  ignores: ['resources/js/ssr.tsx', 'resources/js/app.tsx'],
  languageOptions: {
    ecmaVersion: 'latest', // Use the latest ecmascript standard
    sourceType: 'module', // Allows using import/export statements
    parserOptions: {
      project: ['./tsconfig.json'],
      ecmaFeatures: {
        impliedStrict: true,
        jsx: true,
      },
    },
  },
  extends: [
    eslint.configs.recommended,
    ...tseslint.configs.strictTypeChecked,
    ...tseslint.configs.stylisticTypeChecked,
    eslintConfigPrettier,
  ],
  plugins: {},
  settings: {
    react: {
      version: 'detect',
    },
  },
  rules: {
    semi: 'error',
    'no-unused-vars': 'off',
    '@typescript-eslint/no-unused-vars': [
      'error',
      { caughtErrorsIgnorePattern: 'ignored' },
    ],
    'no-console': 2,
  },
});
