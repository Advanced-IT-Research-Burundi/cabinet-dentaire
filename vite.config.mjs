import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

export default defineConfig(({ mode }) => {
    const comptaEnv = loadEnv(mode, path.resolve(__dirname, 'resources/js/compta'), '');

    return {
        plugins: [
            laravel({
                input: [
                    'resources/js/app.js',
                    'resources/js/compta/src/main.js',
                ],
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
        resolve: {
            alias: {
                '@compta': path.resolve(__dirname, 'resources/js/compta/src'),
            },
        },
        envPrefix: ['VITE_', 'APP_', 'IS_'],
        define: {
            'import.meta.env.APP_URL': JSON.stringify(comptaEnv.APP_URL ?? ''),
            'import.meta.env.APP_URL_LOCAL': JSON.stringify(comptaEnv.APP_URL_LOCAL ?? ''),
            'import.meta.env.IS_PROD': JSON.stringify(comptaEnv.IS_PROD ?? 'false'),
        },
        // theme.css is plain CSS — avoid incomplete Tailwind PostCSS install
        css: {
            postcss: {
                plugins: [],
            },
        },
    };
});
