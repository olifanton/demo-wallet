<template>
    <wlt-page>
        <WltWorkspaceScreenTitle
            :onSelectWallet="onWalletSelectBtnClick"
        ></WltWorkspaceScreenTitle>

        <div v-if="isWalletSelected">
            <WltWorkspaceScreenActiveWallet
                :wallet="currentWallet"
            ></WltWorkspaceScreenActiveWallet>
        </div>

        <div v-if="!isWalletSelected" :class="bem('empty-state')">
            <vs-icon
                icon="rocket_launch"
                size="75px"
                :class="bem('empty-state-icon')"
            ></vs-icon>
            <br />
            <vs-button
                type="border"
                color="dark"
                size="large"
                @click="onWalletSelectBtnClick"
            >Select wallet</vs-button>
        </div>

        <WltModal v-model="isShowWalletsSelectorModal">
            <h1>WIP</h1>
        </WltModal>
    </wlt-page>
</template>

<style lang="scss">
.wlt-workspace-screen {
    &__empty-state {
        text-align: center;
    }

    &__empty-state-icon {
        position: relative;
        right: -5px;
        pointer-events: none;
        user-select: none;
    }
}
</style>

<script lang="ts">
import {defineComponent} from "vue";
import {useRoute} from 'vue-router';
import {useWalletsStore} from "@/stores/wallets-store";
import WltPage from "@/components/layout/WltPage.vue";
import WltScreenTitle from "@/components/ui/WltScreenTitle.vue";
import WltDropdown from "@/components/ui/WltDropdown.vue";
import WltWorkspaceScreenTitle from "@screen/workspace-screen/Title.vue";
import WltWorkspaceScreenActiveWallet from "@screen/workspace-screen/ActiveWallet.vue";
import WltModal from "@/components/ui/WltModal.vue";

export default defineComponent({
    name: "wlt-workspace-screen",
    components: {
        WltModal,
        WltWorkspaceScreenActiveWallet,
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
        isWalletSelected() {
            return useWalletsStore().selectedWalletId !== null;
        },
    },
    data() {
        return {
            addWalletDropdown: false,
            isShowWalletsSelectorModal: false,
        };
    },
    methods: {
        onWalletSelectBtnClick() {
            this.isShowWalletsSelectorModal = true;
        },
    },
})
</script>
