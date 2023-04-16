<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Modules\Wallets\Storages;

class SecretKeyFilter
{
    private ?array $ids = null;

    /**
     * @return string[]|null
     */
    public function getIds(): ?array
    {
        return $this->ids;
    }

    public function withId(string ...$id): self
    {
        $instance = clone $this;
        $instance->ids = $id;

        return $instance;
    }
}
