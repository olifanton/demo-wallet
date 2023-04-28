<template>
    <div :class="bem(false, {'loading': !wallet})">
        <div :class="bem('inner')">
            <div :class="bem('row')">
                <div :class="bem('row-title')">Address</div>
                <div :class="bem('row-value', {'loading': !wallet || isLoading, 'address': true})">
                    <span v-if="wallet && !isLoading">
                        {{ wallet?.address }}
                        <vs-icon
                            icon="content_copy"
                            @click="copyAddress"
                            :class="bem('fast-icon')"
                            title="Copy address"
                        ></vs-icon>
                        <a
                            :href="`https://testnet.tonscan.org/address/${wallet?.address}`"
                            target="_blank"
                            title="Tonscan"
                        >
                            <vs-icon icon="explore" :class="bem('fast-icon')"></vs-icon>
                        </a>
                    </span>
                </div>
            </div>

            <div :class="bem('row')">
                <div :class="bem('row-title', 'name')">Name</div>
                <div :class="bem('row-value', {'loading': !wallet || isLoading, 'name': true})">
                    <span v-if="!isLoading">
                        <span v-if="!isNameEditMode">
                            {{ wallet?.name }}
                        </span>
                        <div v-if="isNameEditMode">
                            <vs-input
                                v-model="wallet.name"
                                @keyup.enter="saveNewWalletName"
                            />
                        </div>
                        <vs-icon
                            v-if="!isNameEditMode"
                            icon="edit"
                            @click="isNameEditMode = true"
                            :class="bem('fast-icon')"
                            title="Edit name"
                        ></vs-icon>
                    </span>
                </div>
            </div>

            <div :class="bem('row')">
                <div :class="bem('row-title')">Balance</div>
                <div :class="bem('row-value', {'loading': !wallet || isLoading, 'balance-ton': true})">
                    <span v-if="wallet && !isLoading">{{ wallet?.balance?.wei }} TON</span>
                </div>
            </div>

            <div :class="bem('row')" v-if="wallet?.balance?.usd && !isLoading">
                <div :class="bem('row-value', 'balance-usd')">${{ wallet?.balance?.usd }}</div>
                <div :class="bem('coingecko-label')">Via CoinGecko</div>
            </div>
        </div>
    </div>
</template>

<style lang="scss">
.wlt-workspace-screen-active-wallet {
    border-radius: calc(var(--border-radius) * 2);
    background: rgb(6,42,60);
    background: linear-gradient(345deg, rgba(6,42,60,1) 0%, rgba(38,53,61,1) 62%);
    color: #fff;
    transition: opacity ease-in-out 0.2s;

    &_loading {
        opacity: 0.9;
        pointer-events: none;
        user-select: none;
    }

    &__inner {
        padding: var(--v-padding);
    }

    &__row {
        margin: calc(var(--v-padding) / 1.4);

        &:first-child {
            margin-top: 0;
        }

        &:last-child {
            margin-bottom: 0;
        }

        &-title {
            font-size: 0.8rem;
            color: #d7d7d7;
        }

        &-value {
            position: relative;
            font-size: 1.2rem;
            font-weight: bold;
            text-overflow: ellipsis;
            overflow: hidden;

            &_loading {
                &:after {
                    content: '';
                    display: block;
                    height: 24px;
                    width: 200px;
                    border-radius: var(--border-radius);
                    background: rgba(221, 221, 221, 0.5);
                    animation: animation-pulse 1s linear infinite;

                    @keyframes animation-pulse {
                        0% {
                            opacity: 1;
                        }

                        50% {
                            opacity: 0.5;
                        }

                        100% {
                            opacity: 1;
                        }
                    }
                }
            }

            input {
                color: rgb(var(--text-color));
            }
        }
    }

    &__coingecko-label {
        font-size: 11px;
    }

    &__fast-icon {
        color: #ffffff;
        text-decoration: none;
        display: inline-block;
        width: 20px;
        height: 19px;
        background-size: contain;
        vertical-align: text-bottom;
        cursor: pointer;
        font-size: 16px;
    }
}
</style>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import type {WalletState} from "@/services/wallets";
import {copyToClipboard} from "@/helpers";
import {useNotificationStore} from "@/stores/notification-store";
import {useWalletsStore} from "@/stores/wallets-store";

export default defineComponent({
    name: "wlt-workspace-screen-active-wallet",
    props: {
        wallet: {
            type: Object as PropType<WalletState>,
            required: false,
            default: null,
        },
        isLoading: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            isNameEditMode: false,
        };
    },
    methods: {
        async copyAddress() {
            if (this.wallet) {
                await copyToClipboard(this.wallet.address);
                useNotificationStore().showText('Success', 'Address copied to clipboard');
            }
        },
        saveNewWalletName() {
            if (this.wallet) {
                useWalletsStore().setNewName(this.wallet.id, this.wallet.name);
            }

            this.isNameEditMode = false;
        },
    },
});
</script>
