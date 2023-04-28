import {inject, injectable} from "tsyringe";
import {Api, ApiResponse, HttpMethod} from "@/services/api";
import {Sum} from "@/services/wallets";
import converter from "ether-converter";

export interface Transaction {
    inProgress: boolean,
    status: string,
    walletId: string,
    destination: {
        address: string,
        domain: string|null,
    },
    amount: Sum,
    date: Date,
    tx: string|null,
}

@injectable()
export class Transactions {
    constructor(
        @inject(Api) private readonly api: Api,
    ) {
    }

    public send(walletId: string, destination: string, amount: BigInt, comment: string): Promise<Transaction> {
        return this
            .api
            .fetch(
                `wallet/${walletId}/transaction`,
                {
                    method: HttpMethod.POST,
                    data: {
                        destination: destination,
                        amount: amount.toString(10),
                        comment: comment,
                    }
                }
            )
            .then((response: ApiResponse) => {
                if (response.isSuccess) {
                    const data = response.data;

                    return {
                        inProgress: true,
                        status: "unknown",
                        walletId: walletId,
                        destination: {
                            address: data.address,
                            domain: data.domain,
                        },
                        amount: {
                            nano: amount,
                            wei: converter(amount, "gwei", "ether"),
                            usd: null,
                        },
                        date: new Date(),
                        tx: null,
                    };
                }

                return Promise.reject(new Error(response.message ?? "Wallet saving error"));
            });
    }
}
