import {createApp} from "vue/dist/vue.esm-bundler.js";
import * as VueRouter from "vue-router";
import {createPinia} from "pinia";
import Vuesax from "vuesax3";
import "reflect-metadata";
import App from "@/App.vue";
import "vuesax3/dist/vuesax.css";
import "material-icons/iconfont/material-icons.css";
import {vfmPlugin} from 'vue-final-modal'
import bemPlugin from "@/plugins/bem-plugin";
import clickOutsidePlugin from "@/plugins/click-outside-plugin";
import "./root.css";
import "@/di";
import routes from "@/routes";

const router = VueRouter.createRouter({
    history: VueRouter.createWebHashHistory(),
    routes,
});
const pinia = createPinia();
const app = createApp(App);

app.use(pinia);
app.use(router);
app.use(vfmPlugin);
app.use(Vuesax, {
    theme: {
        colors: {
            primary: 'rgb(0, 136, 204)',
            success: 'rgb(23, 201, 100)',
            danger: 'rgb(231, 56, 71)',
            warning: 'rgb(204, 94, 4)',
            dark: 'rgb(36, 33, 69)',
            muted: 'rgb(104, 104, 104)',
        }
    }
});
app.use(bemPlugin);
app.use(clickOutsidePlugin);

app.mount('#demo-wallet-app');
