<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Storage;

abstract class SqliteStorage
{
    private static array $existsTables = [];

    public function __construct(
        protected readonly \PDO $pdo,
    )
    {
    }

    protected static function isTableExists(\PDO $pdo, string $table): bool
    {
        if (!isset(self::$existsTables[$table])) {
            self::$existsTables[$table] = false;
            $sth = $pdo
                ->prepare(
                    /** @lang SQLite */"SELECT name FROM sqlite_master WHERE type='table' AND name=:table"
                );

            if ($sth->execute([":table" => $table])) {
                if ($row = $sth->fetch()) {
                    self::$existsTables[$table] = $row['name'] === $table;
                }
            }
        }

        return self::$existsTables[$table];
    }

    /**
     * @return class-string[]
     */
    protected static function dependsOn(): array
    {
        return [];
    }

    abstract protected static function createTable(\PDO $pdo): void;

    protected static function createDepends(\PDO $pdo): void
    {
        foreach (static::dependsOn() as $class) {
            if (!is_subclass_of($class, SqliteStorage::class)) {
                throw new \RuntimeException();
            }

            call_user_func([$class, "createTable"], $pdo);
        }
    }
}
