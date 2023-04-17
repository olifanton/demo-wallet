import {inject, injectable} from "tsyringe";
import BigNumber from "bignumber.js";
import {Api, ApiResponse, HttpMethod} from "@/services/api";

export interface WalletState {
    id: string,
    name: string,
    address: string,
    balance: {
        nano: BigNumber,
        wei: number,
        usd: number|null,
    },
}

export interface ShortWalletData {
    id: string,
    name: string,
    address: string,
}

@injectable()
export class Wallets {
    constructor(
        @inject(Api) private readonly api: Api,
    ) {
    }

    public loadWallet(walletId: string): Promise<WalletState>
    {
        return this
            .api
            .fetch<ApiResponse>(`wallet/state/${walletId}`, {method: HttpMethod.GET})
            .then((response: any) => {
                if (response.isSuccess) {
                    const {data} = response;

                    return {
                        id: data.id,
                        name: data.name,
                        address: data.address,
                        balance: {
                            nano: new BigNumber(data.balance.nano),
                            wei: data.balance.wei,
                            usd: data.balance.usd,
                        },
                    };
                }

                return Promise.reject(new Error(response.message ?? "Wallet state loading error"));
            });
    }

    public getList(): Promise<Array<ShortWalletData>>
    {
        return this
            .api
            .fetch<ApiResponse>(`wallets`, {method: HttpMethod.GET})
            .then((response: any) => {
                if (response.isSuccess) {
                    return response.data.wallets.map(d => {
                        return {
                            id: d.id,
                            name: d.name,
                            address: d.address,
                        };
                    });
                }

                return Promise.reject(new Error(response.message ?? "Wallets list loading error"));
            });
    }
}
