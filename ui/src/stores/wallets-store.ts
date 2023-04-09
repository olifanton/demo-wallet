import {defineStore} from "pinia";

interface WalletsStoreState {
    isLoading: boolean,
    selectedWallet: string|null,
}

export const useWalletsStore = defineStore('wallets', {
    state: () => ({
        isLoading: false,
        selectedWallet: null,
    }) as WalletsStoreState,
    actions: {

    },
});
