<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Infrastructure;

interface TonPriceFetcher
{
    public function getCurrentUSDPrice(): ?float;
}
