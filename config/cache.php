<?php declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Psr\Cache\CacheItemPoolInterface;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\PdoAdapter;
use Symfony\Component\Cache\Psr16Cache;

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
        CacheInterface::class => static function (Container $container) {
            return new Psr16Cache($container->get(CacheItemPoolInterface::class));
        },
    ]);
};
