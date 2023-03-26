import {defineStore} from "pinia";

export const useNotificationStore = defineStore('notification', {
    state: () => ({
        error: {title: null, message: null},
        success: {title: null, message: null},
        text: {title: null, message: null},
    }),
    actions: {
        showError(title: string, message: string) {
            this.error = {
                title,
                message,
            };
        },

        showSuccess(title: string, message: string) {
            this.success = {
                title,
                message,
            };
        },

        showText(title: string, message: string) {
            this.text = {
                title,
                message,
            };
        }
    }
});
