import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { readdirSync } from 'fs';

const collectFiles = (...dirs) =>
    dirs.flatMap(dir =>
        readdirSync(dir, { withFileTypes: true })
            .filter(f => f.isFile())
            .map(f => `${dir}/${f.name}`)
    );

export default defineConfig({
    plugins: [
        laravel({
            input: collectFiles('resources/css', 'resources/js'),
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        origin: 'http://lig.test:5173',
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
