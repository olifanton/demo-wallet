import WltMainScreen from "@screen/WltMainScreen.vue";
import WltCreateWalletScreen from "@screen/WltCreateWalletScreen.vue";
import WltImportWalletScreen from "@screen/WltImportWalletScreen.vue";

export default [
    {path: '/', component: WltMainScreen},
    {path: '/create-wallet', component: WltCreateWalletScreen},
    {path: '/import-wallet', component: WltImportWalletScreen},
];
