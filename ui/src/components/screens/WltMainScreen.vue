<template>
    <wlt-page>
        <h1>Main page screen</h1>
    </wlt-page>
</template>

<script lang="ts">
import {defineComponent} from "vue";
import WltPage from "@/components/layout/WltPage.vue";

export default defineComponent({
    name: "wlt-main-screen",
    components: {
        WltPage,
    },
});
</script>
