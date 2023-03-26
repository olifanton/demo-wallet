import {defineStore} from "pinia";
import {container} from "tsyringe";
import {AppState} from "@/services/app-state";
import {useNotificationStore} from "@/stores/notification-store";

interface AppStoreState {
    isLoading: boolean,
    isInitialized: boolean,
}

const appStateService = container.resolve<AppState>(AppState);

export const useAppStore = defineStore('app', {
    state: () => ({
        isLoading: true,
        isInitialized: false,
    }) as AppStoreState,

    actions: {
        async loadAppState() {
            try {
                this.isLoading = true;
                this.isInitialized = await appStateService.getState();
                this.isLoading = false;
            } catch (e) {
                this.isLoading = false;

                useNotificationStore()
                    .showError(
                        'Application state error',
                        e.message ? e.message : e,
                    );
            }
        },
    }
});
