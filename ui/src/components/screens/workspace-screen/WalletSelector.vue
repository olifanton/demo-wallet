<template>
<div v-if="isLoading"><h1>Loading...</h1></div>
<div v-if="!isLoading">
    <div v-for="(wallet, key) in wallets" :key="key">
        <vs-button @click="onSelect(wallet.id)" type="flat" class="vs-button--block" :title="wallet.address">{{ wallet.name }}</vs-button>
        <br />
    </div>
</div>
</template>

<style lang="scss">

</style>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {useWalletsStore} from "@/stores/wallets-store";
import {useNotificationStore} from "@/stores/notification-store";
import {ShortWalletData} from "@/services/wallets";

type OnSelectWalletCallback = (walletId: string) => void;

export default defineComponent({
    name: "wlt-workspace-screen-wallet-selector",
    props: {
        onSelect: {
            type: Function as PropType<OnSelectWalletCallback>,
            required: true,
        },
    },
    data() {
        return {
            isLoading: true,
            wallets: [] as ShortWalletData[],
        }
    },
    mounted() {
        useWalletsStore()
            .loadWalletsList()
            .then((walletsList) => {
                this.wallets = walletsList;
            })
            .catch((e) => useNotificationStore().showError("List loading error", e.message))
            .finally(() => {
                this.isLoading = false;
            });
    },
});
</script>
