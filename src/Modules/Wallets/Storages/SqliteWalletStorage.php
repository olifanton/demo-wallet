<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Storages;

use Olifanton\DemoWallet\Application\Helpers\Sqlite\Automapper;
use Olifanton\DemoWallet\Application\Storage\QueryHelper;
use Olifanton\DemoWallet\Application\Storage\SqliteStorage;
use Olifanton\DemoWallet\Application\Storage\StatementHelper;
use Olifanton\DemoWallet\Modules\Wallets\Models\Wallet;

class SqliteWalletStorage extends SqliteStorage implements WalletsStorage
{
    /**
     * @var Automapper<Wallet>
     */
    private readonly Automapper $automapper;

    public function __construct(\PDO $pdo)
    {
        $this->automapper = new Automapper(Wallet::class);
        parent::__construct($pdo);
    }

    protected static function dependsOn(): array
    {
        return [
            SqliteSecretKeyStorage::class,
        ];
    }

    /**
     * @return Wallet[]
     * @throws \Throwable
     */
    public function getList(?WalletsFilter $filter = null): array
    {
        self::createTable($this->pdo);

        $sql = /** @lang SQLite */
            "
            SELECT
                T.*
            FROM wallets T
        ";
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

    public function getCount(?WalletsFilter $filter = null): int
    {
        self::createTable($this->pdo);

        $sql = /** @lang SQLite */
            "
            SELECT
                COUNT(T.id) as CNT
            FROM wallets T
        ";
        $sthh = $this->getWhere($filter);

        if ($sthh->isApplicable()) {
            $bind = $sthh->getBindValues();
            $sql = QueryHelper::attachJoin($sql, $sthh, $bind);
            $sql = QueryHelper::attachWhere($sql, $sthh, $bind);
            $sthh->bindAll($bind);
            $sth = $this->pdo->prepare($sql);
            $sth->execute($sthh->getBindValues());

            if ($row = $sth->fetch()) {
                return (int)$row['CNT'];
            }
        }

        return 0;
    }

    public function save(Wallet $wallet): void
    {
        self::createTable($this->pdo);

        if (!$wallet->getId()) {
            throw new \InvalidArgumentException("Id is required");
        }

        if (!$wallet->getType()) {
            throw new \InvalidArgumentException("Type is required");
        }

        if (!$wallet->getSecretKeyId()) {
            throw new \InvalidArgumentException("Secret key id is required");
        }

        $sql = /** @lang SQLite */
            "
            INSERT INTO wallets (id, secret_key_id, name, wallet_type)
            VALUES (:id, :secretKeyId, :name, :walletType)
            ON CONFLICT(id) DO UPDATE SET secret_key_id = :secretKeyId, name = :name, wallet_type = :walletType
        ";
        $sth = $this->pdo->prepare($sql);
        $sth->execute([
            ":id" => $wallet->getId(),
            ":secretKeyId" => $wallet->getSecretKeyId(),
            ":name" => $wallet->getName(),
            ":walletType" => $wallet->getType(),
        ]);
    }

    public function delete(string $walletId): void
    {
        self::createTable($this->pdo);

        $sth = $this->pdo->prepare(/** @lang SQLite */ "DELETE FROM wallets WHERE id = :walletId");
        $sth->execute([":walletId" => $walletId]);
    }

    private function getWhere(?WalletsFilter $filter): StatementHelper
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

    protected static function createTable(\PDO $pdo): void
    {
        parent::createDepends($pdo);

        if (!parent::isTableExists($pdo, "wallets")) {
            $pdo
                ->exec(/** @lang SQLite */ "
                    CREATE TABLE IF NOT EXISTS wallets (
                        id CHARACTER(26) PRIMARY KEY,
                        secret_key_id CHARACTER(26) NOT NULL,
                        name VARCHAR(512) DEFAULT NULL,
                        wallet_type VARCHAR(512) NOT NULL,
                        FOREIGN KEY (secret_key_id) REFERENCES secret_keys(id)
                    ) WITHOUT ROWID;
                ");
            self::isTableExists($pdo, "wallets");
        }
    }
}
