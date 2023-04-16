<?php declare(strict_types=1);

use DI\ContainerBuilder;
use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\HttpMethodsClientInterface;
use Http\Discovery\HttpClientDiscovery;
use Http\Discovery\Psr17FactoryDiscovery;

return static function (ContainerBuilder $builder) {
    $builder->addDefinitions([
        HttpMethodsClientInterface::class => static function () {
            return new HttpMethodsClient(
                HttpClientDiscovery::find(),
                Psr17FactoryDiscovery::findRequestFactory(),
                Psr17FactoryDiscovery::findStreamFactory(),
            );
        },
    ]);
};
