<template>
    <div :class="bem()">
        <slot></slot>
        <div v-if="showed">
            <div :class="bem('target')">
                <div
                    :class="bem('dd')"
                    :style="{ width: `${width}px` }"
                >
                    <slot name="dd"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.wlt-dropdown {
    position: relative;

    &__target {
        position: relative;
    }

    &__dd {
        font-size: 80%;
        position: absolute;
        box-sizing: border-box;
        top: 100%;
        left: 0;
        margin-top: 4px;
        padding: calc(var(--v-padding) / 2) calc(var(--v-padding) / 2.2);
        background: rgb(var(--color-bg));
        border-radius: calc(var(--border-radius) * 2);
        box-shadow: 1px 3px 10px rgba(var(--text-color), 0.15);
    }
}
</style>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
    name: "wlt-dropdown",
    data() {
        return {
            internalShow: true,
        };
    },
    props: {
        showed: {
            type: Boolean,
            required: true,
            default: false,
        },
        width: {
            type: Number,
            default: 300,
        },
    },
});
</script>
