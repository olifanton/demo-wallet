<template>
    <div class="wlt-entry-screen">
        <wlt-page>
            <div class="wlt-entry-screen__i">
                <vs-row>
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="12">
                        <div class="wlt-entry-screen-logo">
                            <img
                                src="/assets/olifanton-icon-colored.svg"
                                alt="Olifanton logo"
                                class="wlt-entry-screen-logo__img"
                            />
                        </div>
                    </vs-col>
                </vs-row>

                <vs-row vs-justify="center">
                    <vs-col vs-w="8" vs-sm="12">
                        <div class="wlt-entry-screen-buttons">
                            <vs-divider>
                                Start using
                            </vs-divider>

                            <vs-button>
                                Create new wallet
                            </vs-button>

                            or

                            <vs-button>
                                Import wallet
                            </vs-button>

                            <span class="wlt-entry-screen-buttons__warn-tooltip">
                                <vs-tooltip text="Testnet only">
                                    <vs-icon icon="warning_amber" icon-pack="material-icons" color="muted"></vs-icon>
                                </vs-tooltip>
                            </span>
                        </div>
                    </vs-col>
                </vs-row>

                <vs-row vs-justify="center">
                    <vs-col vs-type="flex" vs-justify="center" vs-align="center" vs-w="8" vs-sm="12">
                        <vs-alert color="warning" title="Attention!" icon="warning">
                            This wallet is a test and is unsafe. It has been designed to showcase the capabilities of the Olifanton library and debug its public API.
                            Please refrain from using the wallet in a production environment.
                        </vs-alert>
                    </vs-col>
                </vs-row>
            </div>
        </wlt-page>
    </div>
</template>

<style lang="scss">
.wlt-entry-screen {
    --logo-size: 180px;

    display: grid;
    height: 100vh;
    align-items: center;

    &__i {
        margin-top: calc(var(--logo-size) * -1);
    }

    &-logo {
        display: block;
        width: 100%;
        margin: var(--v-padding) 0;
        text-align: center;

        &__img {
            max-width: var(--logo-size);
            height: auto;
            pointer-events: none;
            user-select: none;
            filter: drop-shadow(0px 2px 5px rgba(0, 130, 211, 0.3));
        }
    }

    &-buttons {
        margin: var(--v-padding) 0;
        text-align: center;

        &__warn-tooltip {
            display: inline-block;
            vertical-align: middle;
        }
    }
}
</style>

<script lang="ts">
import {defineComponent} from "vue";
import WltPage from "@/components/layout/WltPage.vue";

export default defineComponent({
    name: "wlt-entry-screen",
    components: {
        WltPage,
    }
});
</script>
