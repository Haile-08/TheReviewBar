import js from '@eslint/js';
import pluginVue from 'eslint-plugin-vue';
import vueTsEslintConfig from '@vue/eslint-config-typescript';
import skipPrettier from 'eslint-config-prettier';
import globals from 'globals';

export default [
  {
    ignores: ['public/**', 'vendor/**', 'node_modules/**', 'resources/js/bootstrap.js'],
  },
  js.configs.recommended,

  ...pluginVue.configs['flat/recommended'],
  ...vueTsEslintConfig(),

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
      'vue/block-lang': 'off',
    },
  },
  
  skipPrettier,
];