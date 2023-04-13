import {inject, injectable} from "tsyringe";
import {Api, ApiResponse, HttpMethod} from "@/services/api";

export interface WalletState {
    id: string,
    name: string,
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
                    };
                }

                return Promise.reject(new Error(response.message ?? "Wallet state loading error"));
            });
    }
}
