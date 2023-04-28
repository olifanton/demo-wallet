<template>
    <vs-button @click="send">Test send</vs-button>
</template>

<style lang="scss">

</style>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import {WalletState} from "@/services/wallets";
import {useTransactionStore} from "@/stores/transaction-store";

export default defineComponent({
    name: "wlt-workspace-screen-send",
    props: {
        wallet: {
            type: Object as PropType<WalletState>,
            required: true,
        },
    },
    methods: {
        send() {
            useTransactionStore().startTransaction(
                this.wallet.id,
                'foobarbazover9000yolo.ton',
                BigInt("10000000"),
                "Hello",
            )
                .then((tx) => {
                    console.log(tx);
                })
        }
    },
});
</script>
