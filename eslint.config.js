import js from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';
import skipPrettier from 'eslint-config-prettier';
import globals from 'globals';

export default [
  {
    // Global ignores
    ignores: ['public/**', 'vendor/**', 'node_modules/**', 'resources/js/bootstrap.js'],
  },
  // 1. Base JS rules
  js.configs.recommended,

  // 2. Vue-specific configuration
  ...pluginVue.configs['flat/recommended'],

  {
    files: ['**/*.vue'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
      globals: {
        ...globals.browser,
      },
    },
    rules: {
      'vue/multi-word-component-names': 'off',
      'vue/no-unused-vars': 'error',
    },
  },

  // 3. Prettier override (Keep this last!)
  skipPrettier,
];