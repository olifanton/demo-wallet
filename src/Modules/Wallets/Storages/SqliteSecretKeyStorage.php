<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Storages;

use Olifanton\DemoWallet\Application\Helpers\Sqlite\Automapper;
use Olifanton\DemoWallet\Application\Storage\SqliteStorage;
use Olifanton\DemoWallet\Modules\Wallets\Models\SecretKey;
use Olifanton\Interop\Bytes;

class SqliteSecretKeyStorage extends SqliteStorage implements SecretKeyStorage
{
    /**
     * @var Automapper<SecretKey>
     */
    private readonly Automapper $automapper;

    public function __construct(\PDO $pdo)
    {
        $this->automapper = new Automapper(SecretKey::class);
        parent::__construct($pdo);
    }

    /**
     * @throws \Throwable
     */
    public function getById(string $secretKeyId): ?SecretKey
    {
        self::createTable($this->pdo);

        $sql = /** @lang SQLite */"SELECT * FROM secret_keys WHERE id = :id";
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
            ":id" => $secretKeyId,
        ]);

        if ($row = $sth->fetch()) {
            return $this->automapper->map($row);
        }

        return null;
    }

    public function delete(string $secretKeyId): void
    {
        self::createTable($this->pdo);

        $this
            ->pdo
            ->prepare(/** @lang SQLite */"DELETE FROM secret_keys WHERE id = :id")
            ->execute([
                ":id" => $secretKeyId,
            ]);
    }

    public function add(SecretKey $key): void
    {
        if (!$key->getId()) {
            throw new \InvalidArgumentException();
        }

        if (!$key->getKeyPair()) {
            throw new \InvalidArgumentException();
        }

        if (!$key->getMnemonicPhrase()) {
            throw new \InvalidArgumentException();
        }

        self::createTable($this->pdo);

        $this
            ->pdo
            ->prepare(/** @lang SQLite */"INSERT INTO secret_keys (id, secret_key, seed) VALUES (:id, :secretKey, :seed)")
            ->execute([
                ":id" => $key->getId(),
                ":secretKey" => Bytes::bytesToHexString($key->getKeyPair()->secretKey),
                ":seed" => $key->getMnemonicPhrase(),
            ]);
    }

    protected static function createTable(\PDO $pdo): void
    {
        self::createDepends($pdo);

        if (!parent::isTableExists($pdo, "secret_keys")) {
            $pdo
                ->exec(/** @lang SQLite */ "
                    CREATE TABLE IF NOT EXISTS secret_keys (
                        id CHARACTER(26) PRIMARY KEY,
                        secret_key TEXT NOT NULL,
                        seed TEXT NOT NULL
                    ) WITHOUT ROWID;
                ");
            self::isTableExists($pdo, "secret_keys");
        }
    }
}
