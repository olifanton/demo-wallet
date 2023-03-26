import {defineStore} from "pinia";
import {container} from "tsyringe";
import {useNotificationStore} from "@/stores/notification-store";
import {WalletCreator} from "@/services/wallet-creator";

interface WalletCreatorStoreState {
    isLoading: boolean,
    words: string[] | null,
}

const walletCreator = container.resolve<WalletCreator>(WalletCreator);

export const useWalletCreatorStore = defineStore('wallet-creator', {
    state: () => ({
        isLoading: false,
        words: null,
    }) as WalletCreatorStoreState,
    actions: {
        async generateWords() {
            const notificationStore = useNotificationStore();

            try {
                this.isLoading = true;
                this.words = await walletCreator.generateWords();
                this.isLoading = false;

                notificationStore.showSuccess(
                    'New wallet',
                    'New wallet successfully created',
                );
            } catch (e) {
                this.isLoading = false;

                notificationStore.showError(
                    'Wallet creation error',
                    e.message ? e.message : e,
                );
            }
        },

        clearState() {
            this.isLoading = false;
            this.words = null;
        }
    }
});

