<?php

namespace Olifanton\DemoWallet\Modules\Wallets\Storages;

use Olifanton\DemoWallet\Modules\Wallets\Models\Wallet;

interface WalletsStorage
{
    /**
     * @return Wallet[]
     */
    public function getList(?WalletsFilter $filter = null): array;

    public function getCount(?WalletsFilter $filter = null): int;

    public function save(Wallet $wallet): Wallet;

    public function delete(string $walletId): void;
}
