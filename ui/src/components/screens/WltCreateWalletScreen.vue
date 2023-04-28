<template>
    <div :class="bem()">
        <wlt-page>
            <WltScreenTitle title="Creating a new wallet" back-to="/"></WltScreenTitle>

            <div
                class="vs-con-loading__container"
                :class="bem('loading')"
                id="wlt-create-wallet-screen-loading"
                v-show="isLoading"
            ></div>

            <div :class="bem('stages')" v-show="isLoading">
                <div
                    v-for="(stage, key) in stages"
                    :key="key"
                    class=""
                    :class="{...bem('stage'), ...{ '_text-muted': stage.value === StageStatus.NONE }}"
                >
                    <vs-icon
                        icon="radio_button_unchecked"
                        icon-pack="material-icons"
                        size="1rem"
                        v-show="stage.value === StageStatus.NONE"
                    ></vs-icon>
                    <vs-icon
                        class="_anim-spin"
                        icon="sync"
                        icon-pack="material-icons"
                        size="1rem"
                        v-show="stage.value === StageStatus.IN_PROGRESS"
                    ></vs-icon>
                    <vs-icon
                        icon="task_alt"
                        icon-pack="material-icons"
                        size="1rem"
                        v-show="stage.value === StageStatus.DONE"
                    ></vs-icon>

                    {{ stage.title }}
                </div>
            </div>

            <ol :class="bem('words')" v-if="!isLoading && words">
                <li v-for="(word, key) in words" :key="key">{{ word }}</li>
            </ol>

            <vs-button v-if="!isLoading && !words" @click="startGenerating">
                Generate new wallet
            </vs-button>

            <div v-if="!isLoading && words" :class="bem('btn')">
                <vs-button @click="copyWords" icon="content_copy" type="flat" color="dark" size="large">
                    Copy words
                </vs-button>
                <vs-button
                    :to="`/${newWalletId ? '?wallet=' + newWalletId : ''}`"
                    size="large"
                    icon="emoji_emotions"
                >
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

    &__stages {
        margin: var(--v-padding) 0;
        font-size: 0.9rem;
    }

    &__stage {
        display: flex;
        align-items: center;

        .vs-icon {
            margin-right: 4px;
        }
    }

    &__words {
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
import {Stage, StageStatus, useWalletCreatorStore} from "@/stores/wallet-creator-store";
import {useNotificationStore} from "@/stores/notification-store";
import {copyToClipboard} from "@/helpers";

export default defineComponent({
    name: "wlt-create-wallet-screen",
    components: {
        WltScreenTitle,
        WltPage,
    },
    data() {
        return {
            newWalletId: null,
        };
    },
    computed: {
        StageStatus() {
            return StageStatus
        },
        isLoading() {
            return useWalletCreatorStore().isLoading;
        },
        words() {
            return useWalletCreatorStore().words;
        },
        stages(): Stage[] {
            return Object.values(useWalletCreatorStore().stages);
        },
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
            useWalletCreatorStore()
                .generateNewWallet()
                .then((newWalletId) => {
                    this.newWalletId = newWalletId;
                });
        },
        copyWords() {
            copyToClipboard(this.words.join(' '))
                .then(() => {
                    useNotificationStore().showText('Success', 'Words copied to clipboard');
                });
        },
    },
});
</script>
