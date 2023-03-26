<template>
<div class="wlt-screen-title">
    <div class="wlt-screen-title__back">
        <vs-button
            color="primary"
            type="flat"
            icon="arrow_back"
            size="small"
            :to="backTo"
            v-if="backTo"
        >
            {{ backTitle }}
        </vs-button>
    </div>

    <div class="wlt-screen-title__text">
        {{ title }}
    </div>
</div>
</template>

<style lang="scss">
.wlt-screen-title {
    margin: var(--v-padding) 0;

    &__back {

    }

    &__text {
        clear: both;
        font-weight: 600;
        font-size: 2rem;
    }
}
</style>

<script lang="ts">
import {defineComponent} from "vue";

export default defineComponent({
    name: 'wlt-screen-title',
    props: {
        backTo: {
            type: String,
            required: false,
            default: null,
        },
        backTitle: {
            type: String,
            required: false,
            default: "Back",
        },
        title: {
            type: String,
            required: true,
        },
    },
})
</script>
