<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Tests\Modules\Wallets\Storages;

use Olifanton\DemoWallet\Modules\Wallets\Models\SecretKey;
use Olifanton\DemoWallet\Modules\Wallets\Storages\SqliteSecretKeyStorage;
use Olifanton\DemoWallet\Tests\Cases\StorageTestCase;

class SqliteSecretKeyStorageTest extends StorageTestCase
{
    private function getInstance(): SqliteSecretKeyStorage
    {
        return new SqliteSecretKeyStorage(
            $this->pdo,
        );
    }

    /**
     * @throws \Throwable
     */
    public function testComplexCrud(): void
    {
        $sk = SecretKey::create(implode(" ", [
            'bring',  'like',    'escape',
            'health', 'chimney', 'pear',
            'whale',  'peasant', 'drum',
            'beach',  'mass',    'garden',
            'riot',   'alien',   'possible',
            'bus',    'shove',   'unable',
            'jar',    'anxiety', 'click',
            'salon',  'canoe',   'lion',
        ]));

        $instance = $this->getInstance();
        $this->assertNull($instance->getById($sk->getId()));
        $instance->add($sk);
        $this->assertEquals($sk, $instance->getById($sk->getId()));
        $instance->delete($sk->getId());
        $this->assertNull($instance->getById($sk->getId()));
    }
}
