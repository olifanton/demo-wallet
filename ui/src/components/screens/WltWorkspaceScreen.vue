<template>
    <wlt-page>
        <WltWorkspaceScreenTitle></WltWorkspaceScreenTitle>

        <div v-if="currentWallet">
            Current wallet: {{ currentWallet.name }}
        </div>
    </wlt-page>
</template>

<style lang="scss">

</style>

<script lang="ts">
import {defineComponent} from "vue";
import {useRoute} from 'vue-router';
import WltPage from "@/components/layout/WltPage.vue";
import WltScreenTitle from "@/components/ui/WltScreenTitle.vue";
import WltDropdown from "@/components/ui/WltDropdown.vue";
import WltWorkspaceScreenTitle from "@screen/workspace-screen/Title.vue";
import {useWalletsStore} from "@/stores/wallets-store";

export default defineComponent({
    name: "wlt-workspace-screen",
    components: {
        WltWorkspaceScreenTitle,
        WltDropdown,
        WltScreenTitle,
        WltPage,
    },
    setup() {
        const route = useRoute();

        if (route.query.wallet && typeof route.query.wallet === "string") {
            useWalletsStore().setCurrentWalletId(route.query.wallet);
        }
    },
    computed: {
        currentWallet() {
            return useWalletsStore().selectedWalletState;
        },
    },
    data() {
        return {
            addWalletDropdown: false,
        };
    },
})
</script>
