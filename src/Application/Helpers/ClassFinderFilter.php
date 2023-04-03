<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Helpers;

class ClassFinderFilter
{
    /**
     * @var class-string|null
     */
    private ?string $subclassOf = null;

    /**
     * @return class-string|null
     */
    public function getSubclassOf(): ?string
    {
        return $this->subclassOf;
    }

    /**
     * @param class-string|null $subclassOf
     */
    public function withSubclassOf(?string $subclassOf): self
    {
        $instance = clone $this;
        $instance->subclassOf = $subclassOf;

        return $instance;
    }
}
