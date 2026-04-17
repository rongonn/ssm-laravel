import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

// Polyfill for Node 18
if (typeof CustomEvent === 'undefined') {
    global.CustomEvent = class extends Event {
        constructor(event, params) {
            super(event, params);
            this.detail = params ? params.detail : undefined;
        }
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.ts',
            ssr: 'resources/js/ssr.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
