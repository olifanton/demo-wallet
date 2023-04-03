<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Tests\Modules\Wallets\Storages;

use Olifanton\DemoWallet\Modules\Wallets\Models\SecretKey;
use Olifanton\DemoWallet\Modules\Wallets\Models\Wallet;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SecretKeyStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SqliteSecretKeyStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SqliteWalletStorage;
use Olifanton\DemoWallet\Modules\Wallets\Storages\WalletsFilter;
use Olifanton\DemoWallet\Tests\Cases\StorageTestCase;
use Ulid\Ulid;

class SqliteWalletStorageTest extends StorageTestCase
{
    private function getInstance(): SqliteWalletStorage
    {
        return new SqliteWalletStorage(
            $this->pdo,
        );
    }

    private function getSecretKeyStorage(): SecretKeyStorage
    {
        return new SqliteSecretKeyStorage(
            $this->pdo,
        );
    }

    /**
     * @throws \Throwable
     */
    public function testSaveNewWalletComplex(): void
    {
        $secretKey = SecretKey::create(implode(" ", [
            'bring',  'like',    'escape',
            'health', 'chimney', 'pear',
            'whale',  'peasant', 'drum',
            'beach',  'mass',    'garden',
            'riot',   'alien',   'possible',
            'bus',    'shove',   'unable',
            'jar',    'anxiety', 'click',
            'salon',  'canoe',   'lion',
        ]));
        $this->getSecretKeyStorage()->add($secretKey);

        $wallet = (new Wallet())
            ->withId((string)Ulid::generate())
            ->withType("foo")
            ->withName("Foo")
            ->withSecretKeyId($secretKey->getId());

        $instance = $this->getInstance();

        $instance->save($wallet);

        $this->assertEquals(
            1,
            $instance->getCount((new WalletsFilter())->withId($wallet->getId())),
        );
    }
}
