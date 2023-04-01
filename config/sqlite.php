<?php declare(strict_types=1);

use Aura\Sql\ExtendedPdo;
use DI\ContainerBuilder;
use Olifanton\DemoWallet\Application\Helpers\ApplicationHelper;

return static function (ContainerBuilder $builder) {
    $builder->addDefinitions([
        PDO::class => static function () {
            return new ExtendedPdo(
                sprintf(
                    'sqlite:%s',
                    ApplicationHelper::getRootDirectory() . "/runtime/db.sqlite",
                ),
                null,
                null,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        },
    ]);
};
