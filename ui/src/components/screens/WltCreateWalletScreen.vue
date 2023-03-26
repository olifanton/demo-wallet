<template>
    <div class="wlt-create-wallet-screen">
        <wlt-page>
            <WltScreenTitle title="Creating a new wallet" back-to="/"></WltScreenTitle>

            <div
                class="wlt-create-wallet-screen__loading vs-con-loading__container"
                id="wlt-create-wallet-screen-loading"
                v-show="isLoading"
            ></div>

            <ol class="wlt-create-wallet-screen-words" v-if="!isLoading && words">
                <li v-for="(word, key) in words" :key="key">{{ word }}</li>
            </ol>

            <vs-button v-if="!isLoading && !words" @click="startGenerating">
                Generate mnemonic and save keys
            </vs-button>

            <div v-if="!isLoading && words" class="wlt-create-wallet-screen__btn">
                <vs-button @click="copyWords" icon="content_copy" type="flat" color="dark" size="large">
                    Copy words
                </vs-button>
                <vs-button to="/" size="large" icon="emoji_emotions">
                    Start using wallet
                </vs-button>
            </div>
        </wlt-page>
    </div>
</template>

<style lang="scss">
.wlt-create-wallet-screen {
    &__loading {
        width: 120px;
        height: 120px;
        border-radius: 10px;
        margin: 20px auto;
    }

    &__btn {
        margin: 0 -8px;

        .vs-button {
            margin: 0 8px;
        }
    }

    &-words {
        column-count: 4;
        column-gap: 20px;
        margin: 20px;
        line-height: 40px;
        font-size: 1.4rem;

        li::marker {
            font-size: 0.9rem;
            color: rgb(var(--vs-muted));
        }
    }
}
</style>

<script lang="ts">
import {defineComponent} from "vue";
import WltPage from "@/components/layout/WltPage.vue";
import WltScreenTitle from "@/components/ui/WltScreenTitle.vue";
import {useWalletCreatorStore} from "@/stores/wallet-creator-store";
import {useNotificationStore} from "@/stores/notification-store";

export default defineComponent({
    name: "wlt-create-wallet-screen",
    components: {
        WltScreenTitle,
        WltPage,
    },
    computed: {
        isLoading() {
            return useWalletCreatorStore().isLoading;
        },
        words() {
            return useWalletCreatorStore().words;
        }
    },
    mounted() {
        this.$vs.loading({
            container: "#wlt-create-wallet-screen-loading",
        });
    },
    unmounted() {
        useWalletCreatorStore().clearState();
    },
    methods: {
        startGenerating() {
            useWalletCreatorStore().generateWords();
        },
        copyWords() {
            navigator
                .clipboard
                .writeText(this.words.join(' '))
                .then(() => {
                    useNotificationStore().showText('Success', 'Words copied to clipboard');
                });
        },
    },
});
</script>
