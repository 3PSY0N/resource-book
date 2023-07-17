import { defineConfig } from "vite";
import symfonyPlugin from "vite-plugin-symfony";
export default defineConfig({
    plugins: [
        symfonyPlugin(),
    ],
    build: {
        rollupOptions: {
            input: {
                app: "./assets/js/app.js",
                choices: "./assets/js/choices.js",
                hljs: "./assets/js/HLJS.js",
                coloris: "./assets/js/coloris.js",
                easymde: "./assets/js/easyMDE.js",
            },
            output: {
                assetFileNames: (assetInfo) => {
                    let extType = assetInfo.name.split(".").at(1);
                    if (/png|jpe?g|svg|webp|avif/i.test(extType)) {
                        extType = "img";
                    } else if (/woff|woff2/i.test(extType)) {
                        extType = "font";
                    }
                    return `assets/${extType}/[name]-[hash][extname]`;
                },
            },
        }
    },
});
