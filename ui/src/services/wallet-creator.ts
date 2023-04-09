import {inject, injectable} from "tsyringe";
import {Api, ApiResponse, HttpMethod} from "@/services/api";

@injectable()
export class WalletCreator {
    constructor(
        @inject(Api) private readonly api: Api,
    ) {}

    public generateWords(): Promise<string[]>
    {
        return this
            .api
            .fetch<ApiResponse>('wallet/generate-words', {method: HttpMethod.POST})
            .then((response: ApiResponse & {data: string[]}) => {
                if (response.isSuccess) {
                    return response.data.map((word) => word);
                }

                return Promise.reject(new Error(response.message ?? "Phrase generation error"));
            });
    }

    async saveWallet(words: string[]): Promise<{walletId: string|null}>
    {
        return this
            .api
            .fetch<ApiResponse>(
                'wallet/save-wallet',
                {
                    method: HttpMethod.POST,
                    data: {
                        words,
                    },
                })
            .then((response: any) => {
                if (response.isSuccess) {
                    return {
                        walletId: response.data?.wallet_id ?? null,
                    }
                }

                return Promise.reject(new Error(response.message ?? "Wallet saving error"));
            });
    }
}
