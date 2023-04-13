import {defineStore} from "pinia";
import {container} from "tsyringe";
import {Wallets, WalletState} from "@/services/wallets";

interface WalletsStoreState {
    isLoading: boolean,
    selectedWalletId: string|null,
    selectedWalletState: WalletState|null,
}

const walletsService = container.resolve<Wallets>(Wallets);

export const useWalletsStore = defineStore('wallets', {
    state: () => ({
        isLoading: false,
        selectedWalletId: null,
        selectedWalletState: null,
    }) as WalletsStoreState,
    actions: {
        async setCurrentWalletId(walletId: string): Promise<void> {
            this.selectedWalletId = walletId;
            this.selectedWalletState = await walletsService.loadWallet(walletId);
        },
    },
});
