<?php

namespace Olifanton\DemoWallet\Tests\Cases;

use Aura\Sql\ExtendedPdo;
use PDO;
use PHPUnit\Framework\TestCase;

abstract class StorageTestCase extends TestCase
{
    private ?string $dbFile = null;

    protected ExtendedPdo $pdo;

    protected function setUp(): void
    {
        $this->dbFile = tempnam(sys_get_temp_dir(), "OLFN_DW_DB_");
        register_shutdown_function(function () {
            $this->clearFile();
        });

        $this->pdo = new ExtendedPdo(
            sprintf(
                'sqlite:%s',
                $this->dbFile,
            ),
            null,
            null,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        );
    }

    protected function tearDown(): void
    {
        $this->clearFile();
    }

    private function clearFile(): void
    {
        if ($this->dbFile && file_exists($this->dbFile)) {
            unlink($this->dbFile);
        }
    }
}
