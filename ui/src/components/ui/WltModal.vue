<template>
    <vue-final-modal
        v-model="isShowed"
        classes="modal-container"
        content-class="modal-content"
    >
        <slot></slot>
    </vue-final-modal>
</template>

<style lang="scss">
.modal-container {
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    display: flex;
    flex-direction: column;
    margin: 0 1rem;
    padding: 1rem;
    border: 1px solid #e2e8f0;
    border-radius: 0.25rem;
    background: #fff;
}
</style>

<script lang="ts">
import {defineComponent} from "vue";
import {computed} from 'vue';

export default defineComponent({
    name: "wlt-modal",
    props: {
        modelValue: {
            type: Boolean,
            default: () => ({}),
        },
    },
    emits: ['update:modelValue'],
    setup(props, { emit }) {
        const isShowed = computed({
            get: () => props.modelValue,
            set: (value) => emit('update:modelValue', value),
        });

        return {
            isShowed,
        };
    },
});
</script>
