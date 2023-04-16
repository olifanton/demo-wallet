<?php declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Olifanton\DemoWallet\Application\Helpers\ApplicationHelper;
use Olifanton\DemoWallet\Application\Http\Server\OpenSwoole\ServerFactory;
use OpenSwoole\Runtime;
use Psr\Log\LoggerInterface;

co::set([
    'hook_flags' => Runtime::HOOK_CURL | Runtime::HOOK_NATIVE_CURL | Runtime::HOOK_TCP,
]);

return static function (ContainerBuilder $builder) {
    $builder->addDefinitions([
        ServerFactory::class => static function (Container $container) {
            $config = [
                'host' => "0.0.0.0",
                'port' => $_ENV["HTTP_LISTEN_PORT"],
                'mode' => SWOOLE_PROCESS,
                'settings' => [
                    'worker_num' => 2,
                    'document_root' => ApplicationHelper::getRootDirectory() . '/public',
                    'enable_static_handler' => true,
                    'http_index_files' => ['index.html']
                ],
            ];

            return new ServerFactory(
                $config,
                $container->get(LoggerInterface::class),
            );
        },
    ]);
};
