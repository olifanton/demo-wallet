<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Infrastructure\Ton;

use DI\Container;
use DI\ContainerBuilder;
use Http\Client\Common\HttpMethodsClient;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;
use Olifanton\DemoWallet\Application\ModuleBootstrapper;
use Olifanton\DemoWallet\Infrastructure\Ton\Helpers\ToncenterHelper;
use Olifanton\Ton\Transport;
use Olifanton\Ton\Transports\Toncenter\ClientOptions;
use Olifanton\Ton\Transports\Toncenter\ToncenterHttpV2Client;
use Olifanton\Ton\Transports\Toncenter\ToncenterTransport;

class TonBootstrapper extends ModuleBootstrapper
{
    /**
     * @throws \Throwable
     */
    public static function boot(ContainerBuilder $builder): void
    {
        $builder->addDefinitions([
            ToncenterHttpV2Client::class => static function () {
                return new ToncenterHttpV2Client(
                    new HttpMethodsClient(
                        HttpClientDiscovery::find(),
                        Psr17FactoryDiscovery::findRequestFactory(),
                        Psr17FactoryDiscovery::findStreamFactory(),
                    ),
                    new ClientOptions(
                        "https://testnet.toncenter.com/api/v2",
                        $_ENV["TONCENTER_API_KEY"],
                    ),
                );
            },

            Transport::class => static function (Container $container) {
                return new ToncenterTransport(
                    $container->get(ToncenterHttpV2Client::class),
                );
            },
        ]);

        ToncenterHelper::warmupHydratorCache();
    }
}
