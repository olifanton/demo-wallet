<template>
    <router-view></router-view>
    <wlt-notifications></wlt-notifications>
</template>

<style lang="scss">

</style>

<script lang="ts">
import { defineComponent } from "vue";
import WltPage from "@/components/layout/WltPage.vue";
import WltEntryScreen from "@/components/screens/WltEntryScreen.vue";
import WltNotifications from "@/components/ui/WltNotifications.vue";

export default defineComponent({
    components: {
        WltNotifications,
        WltEntryScreen,
        WltPage,
    }
});
</script>
