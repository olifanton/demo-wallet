<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Storages;

use Olifanton\DemoWallet\Modules\Wallets\Models\Wallet;

class SqliteWalletStorage implements WalletsStorage
{
    public function getList(?WalletsFilter $filter = null): array
    {
        // TODO: Implement getList() method.
    }

    public function getCount(?WalletsFilter $filter = null): int
    {
        // TODO: Implement getCount() method.
    }

    public function save(Wallet $wallet): Wallet
    {
        // TODO: Implement save() method.
    }

    public function delete(string $walletId): void
    {
        // TODO: Implement delete() method.
    }
}
