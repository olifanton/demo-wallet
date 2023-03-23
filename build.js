const esbuild = require("esbuild");
const vuePlugin = require("esbuild-plugin-vue3")

esbuild.build({
    entryPoints: [
        "ui/app.ts",
    ],
    bundle: true,
    //outfile: "app.js",
    outdir: "public",
    plugins: [
        vuePlugin({
            generateHTML: "ui/index.html",
        }),
    ]
});
