import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { ZiggyVue } from 'ziggy-js';
import { initializeTheme } from '@/composables/useAppearance';

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

createApp(App).use(ZiggyVue).mount('#app')
