<template>
    <wlt-page>
        <WltWorkspaceScreenTitle></WltWorkspaceScreenTitle>
    </wlt-page>
</template>

<style lang="scss">

</style>

<script lang="ts">
import {defineComponent} from "vue";
import WltPage from "@/components/layout/WltPage.vue";
import WltScreenTitle from "@/components/ui/WltScreenTitle.vue";
import WltDropdown from "@/components/ui/WltDropdown.vue";
import WltWorkspaceScreenTitle from "@screen/workspace-screen/Title.vue";

export default defineComponent({
    name: "wlt-workspace-screen",
    components: {WltWorkspaceScreenTitle, WltDropdown, WltScreenTitle, WltPage},
    data() {
        return {
            addWalletDropdown: false,
        };
    }
})
</script>
