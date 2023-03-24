import { createApp } from "vue/dist/vue.esm-bundler.js";
import Vuesax from "vuesax3";
import App from "@/App.vue";
import "vuesax3/dist/vuesax.css";
import "./root.css";

const app = createApp(App);

app.use(Vuesax, {
    colors: {
        primary: 'rgb(0, 136, 204)',
        success: 'rgb(23, 201, 100)',
        danger: 'rgb(242, 19, 93)',
        warning: 'rgb(255, 130, 0)',
        dark: 'rgb(36, 33, 69)',
    }
});

app.mount('#demo-wallet-app');
