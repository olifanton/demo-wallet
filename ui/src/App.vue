<template>
    <router-view></router-view>
</template>

<style lang="scss">

</style>

<script lang="ts">
import { defineComponent } from "vue";
import {container} from "tsyringe";
import WltPage from "@/components/layout/WltPage.vue";
import WltEntryScreen from "@/components/screens/WltEntryScreen.vue";
import {AppState} from "@/services/app-state";

export default defineComponent({
    components: {
        WltEntryScreen,
        WltPage,
    },
    data() {
        return {
            count: 0
        }
    },
    created() {
        const as: AppState = container.resolve(AppState);

        as.getState().then(r => console.log(r));
    }
});
</script>
