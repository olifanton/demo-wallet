<?php declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Olifanton\DemoWallet\Http\Server\SwooleServerFactory;

co::set([
    'hook_flags' => OpenSwoole\Runtime::HOOK_CURL,
]);

return static function (ContainerBuilder $builder) {

    $builder->addDefinitions([
        SwooleServerFactory::class => static function (Container $container) {
            $config = [
                'host' => $_ENV["HTTP_LISTEN_HOST"],
                'port' => $_ENV["HTTP_LISTEN_PORT"],
                'mode' => SWOOLE_PROCESS,
                'settings' => [
                    'worker_num' => OpenSwoole\Util::getCPUNum() * 2,
                    'document_root' => ROOT_DIR . '/public',
                    'enable_static_handler' => true,
                    'http_index_files' => ['index.html']
                ],
            ];

            $instance = new SwooleServerFactory(
                $config,
                $container->get(\Psr\Log\LoggerInterface::class),
            );

            return $instance;
        },
    ]);
};
