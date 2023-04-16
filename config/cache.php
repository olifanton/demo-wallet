<?php declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;

return static function (ContainerBuilder $builder) {
    $builder->addDefinitions([
        CacheItemPoolInterface::class => static function (Container $container) {
            $adapter = new PdoAdapter(
                $container->get(\PDO::class),
                options: [
                    "db_table" => "symfony_cache",
                ],
            );
            $adapter->setLogger($container->get(\Psr\Log\LoggerInterface::class));

            return $adapter;
        },
    ]);
};
