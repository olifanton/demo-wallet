<?php declare(strict_types=1);

use DI\Container;
use DI\ContainerBuilder;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Olifanton\DemoWallet\Application\Helpers\ApplicationHelper;
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
                sprintf(
                    "%s/runtime/logs/error_%s.log",
                    ApplicationHelper::getRootDirectory(),
                    date("d_m_Y"),
                ),
                LogLevel::ERROR,
            );
            $logger->pushHandler($errorHandler);

            return $logger;
        },
    ]);
};
