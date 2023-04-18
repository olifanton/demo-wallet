<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Infrastructure\CoinGecko;

use Http\Client\Common\HttpMethodsClientInterface;
use Olifanton\DemoWallet\Application\Infrastructure\TonPriceFetcher;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;

class CGPriceFetcher implements TonPriceFetcher
{
    private const CACHE_KEY = 'toncoin_price';
    private const CACHE_TTL = "PT30M";
    private const CG_PRICE_URL = 'https://api.coingecko.com/api/v3/simple/price?ids=the-open-network&vs_currencies=usd';

    public function __construct(
        private readonly HttpMethodsClientInterface $httpClient,
        private readonly CacheItemPoolInterface $cacheItemPool,
        private readonly LoggerInterface $logger,
    )
    {
    }

    public function getCurrentUSDPrice(): ?float
    {
        try {
            $cachedPrice = $this->cacheItemPool->getItem(self::CACHE_KEY);

            if ($cachedPrice->isHit()) {
                $this->logger->debug("[CGPriceFetcher] Cache hit");

                return $cachedPrice->get();
            }

            $this->logger->debug("[CGPriceFetcher] Start CoinGecko request...");
            $response = $this
                ->httpClient
                ->get(
                    self::CG_PRICE_URL,
                );
            $json = $response->getBody()->getContents();
            $data = json_decode($json, true, 32, JSON_THROW_ON_ERROR);

            if (isset($data["the-open-network"]["usd"])) {
                $price = $data["the-open-network"]["usd"];
                $cachedPrice
                    ->set($price)
                    ->expiresAfter(new \DateInterval(self::CACHE_TTL));
                $this->cacheItemPool->save($cachedPrice);

                $this
                    ->logger
                    ->debug(
                        "[CGPriceFetcher] Fresh TON price fetched from CoinGecko: " . $price . " USD",
                    );

                return $price;
            }
        } catch (\Throwable $e) {
            $this
                ->logger
                ->error(
                    "[CGPriceFetcher] Price fetching error: " . $e->getMessage(),
                    [
                        "exception" => $e,
                    ]
                );
        }

        return null;
    }
}
