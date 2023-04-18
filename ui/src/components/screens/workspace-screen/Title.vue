<template>
    <WltScreenTitle
        title="Wallets"
        :logo="true"
    >
        <WltDropdown
            :showed="addWalletDropdown"
            width="200"
            v-click-outside="onClickOutside"
        >
            <vs-button
                @click="addWalletDropdown = !addWalletDropdown"
                type="flat"
                color="dark"
                :active="addWalletDropdown"
            >
                Add wallet
            </vs-button>
            <template #dd>
                <vs-button to="/create-wallet" class="vs-button--block" type="flat">
                    Create
                </vs-button>
                <vs-button to="/import-wallet" class="vs-button--block" type="flat">
                    Import
                </vs-button>
            </template>
        </WltDropdown>
        <vs-button
            @click="onSelectWallet"
            type="flat"
            color="dark"
            :active="addWalletDropdown"
        >
            Select wallet
        </vs-button>
    </WltScreenTitle>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import WltDropdown from "@/components/ui/WltDropdown.vue";
import WltScreenTitle from "@/components/ui/WltScreenTitle.vue";

export default defineComponent({
    name: "wlt-workspace-screen-title",
    components: {WltDropdown, WltScreenTitle},
    props: {
        onSelectWallet: {
            type: Function,
            required: false,
            default: () => {},
        }
    },
    data() {
        return {
            addWalletDropdown: false,
        };
    },
    methods: {
        onClickOutside() {
            this.addWalletDropdown = false;
        },
    },
})
</script>
