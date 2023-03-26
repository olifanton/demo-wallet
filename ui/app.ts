import { createApp } from "vue/dist/vue.esm-bundler.js";
import * as VueRouter from "vue-router";
import Vuesax from "vuesax3";
import "reflect-metadata";
import App from "@/App.vue";
import "vuesax3/dist/vuesax.css";
import "material-icons/iconfont/material-icons.css";
import "./root.css";
import "@/di";
import routes from "@/routes";

const router = VueRouter.createRouter({
    history: VueRouter.createWebHashHistory(),
    routes,
});

const app = createApp(App);

app.use(Vuesax, {
    theme: {
        colors: {
            primary: 'rgb(0, 136, 204)',
            success: 'rgb(23, 201, 100)',
            danger: 'rgb(242, 19, 93)',
            warning: 'rgb(232, 153, 2)',
            dark: 'rgb(36, 33, 69)',
            muted: 'rgb(104, 104, 104)',
        }
    }
});
app.use(router);

app.mount('#demo-wallet-app');
