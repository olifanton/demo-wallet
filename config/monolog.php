<?php declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

return static function (ContainerBuilder $builder) {
    $builder->addDefinitions([
        LoggerInterface::class => static function (Container $container) {
            $logger = new Logger("olfnt_demo_wallet");

            $formatter = new LineFormatter();
            $formatter->setMaxNormalizeDepth(20);
            $formatter->setMaxNormalizeItemCount(10000);

            $defaultHandler = new StreamHandler(
                'php://stdout',
                LogLevel::DEBUG,
            );
            $defaultHandler->setFormatter($formatter);
            $logger->pushHandler($defaultHandler);

            $errorHandler = new StreamHandler(
                ROOT_DIR . "/runtime/logs/error_" . date("d_m_Y") . ".log",
                LogLevel::ERROR,
            );
            $logger->pushHandler($errorHandler);

            return $logger;
        },
    ]);
};
