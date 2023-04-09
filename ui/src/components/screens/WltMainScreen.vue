<template>
    <div :class="bem()">
        <div :class="bem('loading')" v-if="isLoading">
            <div :class="bem('logo')">
                <img
                    src="/assets/olifanton-icon-colored.svg"
                    alt="Olifanton logo"
                    :class="bem('logo-img')"
                />
            </div>
        </div>
        <div :class="bem('loaded')" v-if="!isLoading">
            <WltEntryScreen v-if="!isApplicationInitialized"></WltEntryScreen>
            <WltWorkspaceScreen v-if="isApplicationInitialized"></WltWorkspaceScreen>
        </div>
    </div>
</template>

<style lang="scss">
.wlt-main-screen {
    &__loading {
        display: grid;
        height: 100vh;
        align-items: center;
    }

    &__logo {
        display: block;
        width: 100%;
        margin: var(--v-padding) 0;
        text-align: center;
    }

    &__logo-img {
        max-width: 180px;
        height: auto;
        pointer-events: none;
        user-select: none;
        filter: drop-shadow(0px 2px 5px rgba(0, 130, 211, 0.3));
    }

    &__loaded {

    }
}
</style>

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
    mounted() {
        useAppStore().loadAppState();
    },
    computed: {
        isApplicationInitialized() {
            return useAppStore().isInitialized;
        },
        isLoading() {
            return useAppStore().isLoading;
        },
    },
    created() {
        useAppStore().loadAppState();
    },
});
</script>
