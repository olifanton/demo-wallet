<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Storages;

class WalletsFilter
{
    private ?array $ids = null;

    private ?string $secretKeyId = null;

    /**
     * @return string[]|null
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    public function withId(string ...$walletId): self
    {
        $instance = clone $this;
        $instance->ids = $walletId;

        return $instance;
    }

    public function getSecretKeyId(): ?string
    {
        return $this->secretKeyId;
    }

    public function withSecretKeyId(string $secretKeyId): self
    {
        $instance = clone $this;
        $instance->secretKeyId = $secretKeyId;

        return $instance;
    }
}
