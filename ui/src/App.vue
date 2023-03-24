<template>
    <div class="center grid">
        <vs-row>
            <vs-col vs-type="flex" vs-justify="center" vs-align="center" w="12">
                <vs-button
                    @click.prevent="count++"
                    danger
                    flat
                >
                    Count is: {{ count }}
                </vs-button>

                Kek!
            </vs-col>
        </vs-row>
    </div>
</template>

<style lang="text/scss">

</style>

<script lang="ts">
import { defineComponent } from "vue";

export default defineComponent({
    data() {
        return {
            count: 0
        }
    }
});
</script>
