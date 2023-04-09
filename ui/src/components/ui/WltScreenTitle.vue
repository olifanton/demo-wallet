<template>
<div :class="bem()">
    <div :class="bem('back')">
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

    <div :class="bem('text-layout')">
        <div :class="bem('logo')" v-if="logo"></div>
        <div :class="bem('text')">
            {{ title }}
        </div>
        <slot></slot>
    </div>
</div>
</template>

<style lang="scss">
.wlt-screen-title {
    margin: var(--v-padding) 0;

    &__back {

    }

    &__text-layout {
        display: flex;
        align-items: center;
        clear: both;
    }

    &__text {
        margin-right: 8px;
        clear: both;
        font-weight: 600;
        font-size: 2rem;
    }

    &__logo {
        width: 49px;
        height: 49px;
        margin-right: 8px;
        background: url("/assets/olifanton-icon-mono.svg") no-repeat center center;
        background-size: contain;
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
        logo: {
            type: Boolean,
            default: false,
        },
    },
})
</script>
