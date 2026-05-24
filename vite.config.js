import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwindcss from '@tailwindcss/vite'

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/voice.js'],
            refresh: true,
        }),
    ],
    optimizeDeps: {
        exclude: ['@ricky0123/vad-web', 'onnxruntime-web'],
    },
})