<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Storages;

use Olifanton\DemoWallet\Modules\Wallets\Models\SecretKey;
use Olifanton\TypedArrays\Uint8Array;

interface SecretKeyStorage
{
    public function getById(string $secretKeyId): ?SecretKey;

    public function getBySecretKeyValue(Uint8Array $secretKey): ?SecretKey;

    public function delete(string $secretKeyId): void;

    public function add(SecretKey $key): void;

    public function getList(?SecretKeyFilter $filter = null): array;
}
