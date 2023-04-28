import {defineStore} from "pinia";
import {container} from "tsyringe";
import {Transaction, Transactions} from "@/services/transactions";

interface TransactionStoreState {
    isLoading: boolean,
    isTxInProgress: boolean,
    transactions: Array<Transaction>,
}

const transactionService = container.resolve<Transactions>(Transactions);

export const useTransactionStore = defineStore('transaction', {
    state: () => ({
        isLoading: false,
        isTxInProgress: false,
        transactions: [],
    }) as TransactionStoreState,
    actions: {
        async loadTransactions(walletId: string): Promise<void> {
            return Promise.resolve();
        },
        async startTransaction(walletId: string,
                               destination: string,
                               amount: BigInt,
                               comment: string = "",
        ): Promise<Transaction> {
            this.$patch((state: TransactionStoreState) => {
                state.isTxInProgress = true;
            });

            return transactionService.send(
                walletId,
                destination,
                amount,
                comment,
            )
                .then((trx) => {
                    this.$patch((state: TransactionStoreState) => {
                        state.transactions.push(trx);
                    });

                    return trx;
                })
                .finally(() => {
                    this.$patch((state: TransactionStoreState) => {
                        state.isTxInProgress = false;
                    });
                })
        },
    },
});
