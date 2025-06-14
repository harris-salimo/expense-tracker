import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { initializeTheme } from '@workspace/ui/composables';
import { router } from './router';
import { VueQueryPlugin } from '@tanstack/vue-query';

// Extend ImportMeta interface for Vite...
declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;
        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

// const appName = import.meta.env.VITE_APP_NAME || 'Vola';

// This will set light / dark mode on page load...
initializeTheme();

createApp(App).use(router).use(VueQueryPlugin).mount('#app');
