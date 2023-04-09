const esbuild = require("esbuild");
const vuePlugin = require("esbuild-plugin-vue3");
const { copy } = require("esbuild-plugin-copy");
const envFilePlugin = require('esbuild-envfile-plugin');
const commandLineArgs = require('command-line-args');

/**
 * @typedef {{
 *     watch: boolean,
 * }} BuildOptions
 */

/**
 * @type {BuildOptions}
 */
const options = commandLineArgs([
    {name: 'watch', alias: 'w', type: Boolean, defaultOption: false},
]);

(async () => {
    const ctx = await esbuild.context({
        entryPoints: [
            "ui/app.ts",
        ],
        bundle: true,
        outdir: "public",
        external: ["env", "/assets/*"],
        plugins: [
            vuePlugin({
                generateHTML: "ui/index.html",
            }),
            envFilePlugin,
            copy({
                resolveFrom: 'cwd',
                assets: {
                    from: ['./ui/static/**/*'],
                    to: ['./public'],
                },
                watch: true,
            }),
        ],
        loader: {
            '.woff': 'file',
            '.woff2': 'file',
        },
    });

    if (options.watch) {
        console.log('Watching...');

        await ctx.watch();
    } else {
        await ctx.rebuild();
        process.exit();
    }
})();

