import {defineStore} from "pinia";
import {container} from "tsyringe";
import {useNotificationStore} from "@/stores/notification-store";
import {WalletCreator} from "@/services/wallet-creator";

export enum StageStatus {
    NONE,
    IN_PROGRESS,
    DONE,
}

export interface Stage {
    title: string,
    value: StageStatus,
}

interface WalletCreatorStoreState {
    isLoading: boolean,
    words: string[] | null,
    stages: {
        mnemonic: Stage,
        saving: Stage,
    }
}

const walletCreator = container.resolve<WalletCreator>(WalletCreator);

export const useWalletCreatorStore = defineStore('wallet-creator', {
    state: () => ({
        isLoading: false,
        words: null,
        stages: {
            mnemonic: {
                title: "Mnemonic phrase generation",
                value: StageStatus.NONE,
            },
            saving: {
                title: "Wallet saving",
                value: StageStatus.NONE,
            },
        },
    }) as WalletCreatorStoreState,
    actions: {
        async generateNewWallet(): Promise<string> {
            const notificationStore = useNotificationStore();

            try {
                this.isLoading = true;

                this.$patch((state) => {
                    state.stages.mnemonic.value = StageStatus.IN_PROGRESS;
                });
                this.words = await walletCreator.generateWords();
                this.$patch((state) => {
                    state.stages.mnemonic.value = StageStatus.DONE;
                });

                this.$patch((state) => {
                    state.stages.saving.value = StageStatus.IN_PROGRESS;
                });
                const data = await walletCreator.saveWallet(this.words.map((word: string) => word));
                this.$patch((state) => {
                    state.stages.saving.value = StageStatus.DONE;
                });

                this.isLoading = false;

                notificationStore.showSuccess(
                    'New wallet',
                    'New wallet successfully created',
                );

                return data.walletId;
            } catch (e) {
                this.isLoading = false;

                notificationStore.showError(
                    'Wallet creation error',
                    e.message ? e.message : e,
                );
            }
        },

        async importWallet(words: Array<string>) {
            this.clearState();

            const notificationStore = useNotificationStore();

            try {
                this.isLoading = true;
                await walletCreator.saveWallet(words.map((word: string) => word));
                this.isLoading = false;

                notificationStore.showSuccess(
                    'New wallet',
                    'New wallet successfully imported',
                );
            } catch (e) {
                this.isLoading = false;

                notificationStore.showError(
                    'Wallet import error',
                    e.message ? e.message : e,
                );
            }
        },

        clearState() {
            this.isLoading = false;
            this.words = null;
            this.stages.mnemonic.value = StageStatus.NONE;
            this.stages.saving.value = StageStatus.NONE;
        },
    }
});
