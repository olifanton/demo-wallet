<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Infrastructure\Ton;

use DI\Container;
use DI\ContainerBuilder;
use Http\Client\Common\HttpMethodsClientInterface;
use Olifanton\DemoWallet\Application\ModuleBootstrapper;
use Olifanton\DemoWallet\Infrastructure\Ton\Helpers\ToncenterHelper;
use Olifanton\Ton\Dns\DnsClient;
use Olifanton\Ton\Transport;
use Olifanton\Ton\Transports\Toncenter\ClientOptions;
use Olifanton\Ton\Transports\Toncenter\ToncenterHttpV2Client;
use Olifanton\Ton\Transports\Toncenter\ToncenterTransport;
use Olifanton\Ton\Transports\Toncenter\ToncenterV2Client;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;

class TonBootstrapper extends ModuleBootstrapper
{
    /**
     * @throws \Throwable
     */
    public static function boot(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            ToncenterV2Client::class => static function (Container $container) {
                return new ToncenterHttpV2Client(
                    $container->get(HttpMethodsClientInterface::class),
                    new ClientOptions(
                        "https://testnet.toncenter.com/api/v2",
                        $_ENV["TONCENTER_API_KEY"],
                    ),
                );
            },

            Transport::class => static function (Container $container) {
                return new ToncenterTransport(
                    $container->get(ToncenterV2Client::class),
                );
            },

            DnsClient::class => static function (Container $container) {
                $client = new DnsClient($container->get(Transport::class));
                $client->setCache($container->get(CacheInterface::class));
                $client->setLogger($container->get(LoggerInterface::class));

                return $client;
            },
        ]);

        ToncenterHelper::warmupHydratorCache();
    }
}
