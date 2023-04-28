import {defineStore} from "pinia";
import {container} from "tsyringe";
import {ShortWalletData, Wallets, WalletState} from "@/services/wallets";

interface WalletsStoreState {
    isWalletLoading: boolean,
    selectedWalletId: string|null,
    selectedWalletState: WalletState|null,
}

const walletsService = container.resolve<Wallets>(Wallets);

export const useWalletsStore = defineStore('wallets', {
    state: () => ({
        isWalletLoading: false,
        selectedWalletId: null,
        selectedWalletState: null,
    }) as WalletsStoreState,
    actions: {
        async setCurrentWalletId(walletId: string): Promise<void> {
            this.selectedWalletId = walletId;
            this.$patch((state: WalletsStoreState) => {
                state.isWalletLoading = true;
            });
            this.selectedWalletState = await walletsService.loadWallet(walletId);
            this.$patch((state: WalletsStoreState) => {
                state.isWalletLoading = false;
            });
        },
        async loadWalletsList(): Promise<ShortWalletData[]> {
            return walletsService.getList();
        },
        async setNewName(walletId: string, name: string): Promise<void> {
            return walletsService.updateWallet(walletId, {
                name,
            });
        },
    },
});
