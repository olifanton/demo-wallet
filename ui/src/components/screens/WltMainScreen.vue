<template>
    <wlt-page>
        <WltEntryScreen v-if="!isApplicationInitialized"></WltEntryScreen>
        <WltWorkspaceScreen v-if="isApplicationInitialized"></WltWorkspaceScreen>
    </wlt-page>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import WltPage from "@/components/layout/WltPage.vue";
import {useAppStore} from "@/stores/app-store";
import WltEntryScreen from "@screen/WltEntryScreen.vue";
import WltWorkspaceScreen from "@screen/WltWorkspaceScreen.vue";

export default defineComponent({
    name: "wlt-main-screen",
    components: {
        WltWorkspaceScreen,
        WltEntryScreen,
        WltPage,
    },
    data() {
        const appStore = useAppStore();

        return {
            isApplicationLoading: appStore.isLoading,
            isApplicationInitialized: appStore.isInitialized,
        };
    },
    created() {
        const appStore = useAppStore();

        appStore.$subscribe((mutation: WltStoreMutation & any, state) => {
            if (mutation.storeId === 'app' && mutation.events.type === 'set' && mutation.events.key === 'isLoading') {
                if (state.isLoading) {
                    this.$vs.loading();
                } else {
                    this.$vs.loading.close();
                }
            }
        });

        this.$vs.loading();
        useAppStore().loadAppState();
    },
});
</script>
