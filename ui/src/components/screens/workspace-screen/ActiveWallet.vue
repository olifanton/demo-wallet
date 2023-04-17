<template>
    <div :class="bem(false, {'loading': !wallet})">
        <div :class="bem('inner')">
            <div :class="bem('row')">
                <div :class="bem('row-title')">Address</div>
                <div :class="bem('row-value', {'loading': !wallet, 'address': true})">
                    <span v-if="wallet">
                        {{ wallet?.address }}
                        <a
                            :href="`https://testnet.tonscan.org/address/${wallet?.address}`"
                            target="_blank"
                            :class="bem('scanner-icon')"
                            title="Tonscan"
                        ></a>
                    </span>
                </div>
            </div>

            <div :class="bem('row')">
                <div :class="bem('row-title', 'name')">Name</div>
                <div :class="bem('row-value', {'loading': !wallet, 'name': true})">{{ wallet?.name }}</div>
            </div>

            <div :class="bem('row')">
                <div :class="bem('row-title')">Balance</div>
                <div :class="bem('row-value', {'loading': !wallet, 'balance-ton': true})">
                    <span v-if="wallet">{{ wallet?.balance?.wei }} TON</span>
                </div>
            </div>

            <div :class="bem('row')" v-if="wallet?.balance?.usd">
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
        }
    }

    &__coingecko-label {
        font-size: 11px;
    }

    &__scanner-icon {
        display: inline-block;
        width: 24px;
        height: 24px;
        background: url(/assets/tonscan-icon.svg) no-repeat center center;
        background-size: contain;
        vertical-align: text-bottom;
    }
}
</style>

<script lang="ts">
import {defineComponent, PropType} from "vue";
import type {WalletState} from "@/services/wallets";

export default defineComponent({
    name: "wlt-workspace-screen-active-wallet",
    props: {
        wallet: {
            type: Object as PropType<WalletState>,
            required: false,
            default: null,
        },
    },
});
</script>
