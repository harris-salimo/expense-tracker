import prettier from "eslint-config-prettier";

export default [
  {
    ignores: ["node_modules", "tailwind.config.js"],
  },
  {
    rules: {
      "@typescript-eslint/no-explicit-any": "off",
    },
  },
  prettier,
];
