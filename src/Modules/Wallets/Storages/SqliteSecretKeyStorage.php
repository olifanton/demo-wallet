<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Storages;

use Olifanton\DemoWallet\Application\Helpers\Sqlite\Automapper;
use Olifanton\DemoWallet\Application\Storage\QueryHelper;
use Olifanton\DemoWallet\Application\Storage\SqliteStorage;
use Olifanton\DemoWallet\Application\Storage\StatementHelper;
use Olifanton\DemoWallet\Modules\Wallets\Models\SecretKey;
use Olifanton\Interop\Bytes;
use Olifanton\TypedArrays\Uint8Array;

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
    public function getList(?SecretKeyFilter $filter = null): array
    {
        self::createTable($this->pdo);

        $sql = /** @lang SQLite */"SELECT T.* FROM secret_keys T";
        $result = [];
        $sthh = $this->getWhere($filter);

        if ($sthh->isApplicable()) {
            $bind = $sthh->getBindValues();
            $sql = QueryHelper::attachJoin($sql, $sthh, $bind);
            $sql = QueryHelper::attachWhere($sql, $sthh, $bind);
            $sthh->bindAll($bind);
            $sth = $this->pdo->prepare($sql);
            $sth->execute($sthh->getBindValues());

            while ($row = $sth->fetch()) {
                $result[] = $this->automapper->map($row);
            }
        }

        return $result;
    }

    /**
     * @throws \Throwable
     */
    public function getById(string $secretKeyId): ?SecretKey
    {
        $items = $this->getList((new SecretKeyFilter())->withId($secretKeyId));

        if (!empty($items)) {
            return $items[0];
        }

        return null;
    }

    /**
     * @throws \Throwable
     */
    public function getBySecretKeyValue(Uint8Array $secretKey): ?SecretKey
    {
        self::createTable($this->pdo);

        $sql = /** @lang SQLite */"SELECT * FROM secret_keys WHERE secret_key = :secretKey";
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
            ":secretKey" => Bytes::bytesToHexString($secretKey),
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

    private function getWhere(?SecretKeyFilter $filter): StatementHelper
    {
        $sh = new StatementHelper();
        $where = [];

        if ($filter) {
            if ($in = QueryHelper::getInPlaceholders($filter->getIds(), "ids")) {
                $where[] = [
                    "AND",
                    "T.id IN (" . $in->getIn() . ")"
                ];
                $sh->bindAll($in->getBindValues());
            }
        }

        $sh->setWhereArray($where);

        return $sh;
    }
}
