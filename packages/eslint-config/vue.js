import vue from "eslint-plugin-vue";
import { vueTsConfigs } from '@vue/eslint-config-typescript';

export default [
  vue.configs["flat/essential"],
  vueTsConfigs.recommended,
  {
    ignores: ["public"],
  },
  {
    rules: {
      "vue/multi-word-component-names": "off",
      "@typescript-eslint/no-explicit-any": "off",
    },
  },
];
