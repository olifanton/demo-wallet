<template>
<div :class="bem()">
    <wlt-page>
        <WltScreenTitle title="Importing a wallet" back-to="/"></WltScreenTitle>

        <div :class="bem('warn')">
            <vs-alert color="warning">
                Use Testnet only wallets!
            </vs-alert>
        </div>

        <p>
            Please enter the 24 words of your seed phrase to import your wallet
        </p>

        <div :class="bem('words')">
            <div
                :class="bem('word')"
                v-for="wordKey in Object.keys(words)"
                :key="wordKey"
            >
                <vs-input
                    size="large"
                    :placeholder="parseInt(wordKey.replace('word_', '')) + 1"
                    v-model="words[wordKey]"
                    :danger="isInvalidWord(words[wordKey], wordKey)"
                    :success="isValidWord(words[wordKey], wordKey)"
                    @change="touched[wordKey] = true"
                    @paste="magicPaste"
                    list="bip39"
                />
            </div>
        </div>

        <datalist id="bip39">
            <option v-for="(word, key) in bip39" :value="word" :key="key" />
        </datalist>

        <div :class="bem('btn')">
            <vs-button
                size="large"
                icon="account_balance_wallet"
                :disabled="!isAllWordsValid"
                :loading="isImportProgress"
                @click="importWallet"
            >
                Import wallet
            </vs-button>
        </div>
    </wlt-page>
</div>
</template>

<style lang="scss">
.wlt-import-wallet-screen {
    &__warn {
        margin: var(--v-padding) 0;
    }

    &__words {
        max-width: 540px;
        margin: var(--v-padding) auto;
        column-count: 2;
        column-gap: 20px;
    }

    &__word {
        margin-bottom: 16px;

        .vs-con-input-label {
            width: 100%;
        }
    }

    &__btn {
        display: flex;
        margin: var(--v-padding) 0;
        justify-content: center;
    }
}
</style>

<script lang="ts">
import {defineComponent} from "vue";
import WltPage from "@/components/layout/WltPage.vue";
import WltScreenTitle from "@/components/ui/WltScreenTitle.vue";
import {isValidWord} from "@/services/bip39";
import pip39English from "@/services/data/bip39-english";
import {useWalletCreatorStore} from "@/stores/wallet-creator-store";

export default defineComponent({
    name: "wlt-import-wallet-screen",
    components: {WltScreenTitle, WltPage},
    data() {
        return {
            words: (() => {
                const words = {};

                Array
                    .from({length: 24}, (x, i) => '')
                    .forEach((v, i) => {
                        words[`word_${i}`] = v;
                });

                return words;
            })(),
            touched: {},
            bip39: pip39English,
        };
    },
    computed: {
        isAllWordsValid() {
            if (this.words) {
                return Object
                    .values(this.words)
                    .map((w) => isValidWord(w))
                    .indexOf(false) === -1;
            }

            return false;
        },
        isImportProgress() {
            return useWalletCreatorStore().isLoading;
        },
    },
    methods: {
        isInvalidWord(word: string, wordKey: string): boolean {
            if (this.touched[wordKey]) {
                if (word.length > 0) {
                    if (/ /g.test(word)) {
                        return true;
                    }

                    if (!isValidWord(word)) {
                        return true;
                    }
                }
            }

            return false;
        },
        isValidWord(word: string, wordKey: string): boolean {
            if (this.touched[wordKey]) {
                return isValidWord(word);
            }

            return false;
        },
        magicPaste(evt) {
            setTimeout(() => {
                const rawWords = evt.target.value;

                if (rawWords.length) {
                    const wordsList = rawWords
                        .trim()
                        .split(/\s/);

                    if (wordsList.length === 24) {
                        wordsList.forEach((w, i) => {
                            const wKey = `word_${i}`;
                            this.words[wKey] = w;
                            this.touched[wKey] = true;
                        });
                    }
                }
            }, 0);
        },
        importWallet() {
            if (this.isAllWordsValid) {
                useWalletCreatorStore()
                    .importWallet(Object.values(this.words))
                    .then(() => {
                        this.$router.replace('/');
                    });
            }
        },
    }
});
</script>
