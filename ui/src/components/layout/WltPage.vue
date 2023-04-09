<template>
    <div :class="bem()">
        <div :class="bem('i')">
            <div class="grid">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.wlt-page {
    padding: 0 16px;
    min-height: 100vh;

    &__i {
        max-width: 960px;
        margin: 0 auto;
    }
}
</style>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
    name: "wlt-page",
});
</script>
