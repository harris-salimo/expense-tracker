import baseConfig from '@workspace/eslint-config/base';
import vueConfig from '@workspace/eslint-config/vue';

import { defineConfigWithVueTs } from '@vue/eslint-config-typescript';

export default defineConfigWithVueTs(
    ...baseConfig,
    ...vueConfig
);
