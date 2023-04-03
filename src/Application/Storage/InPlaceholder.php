<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Storage;

class InPlaceholder
{
    /**
     * @var array<int, mixed>
     */
    private array $bindValues;

    public function __construct(private readonly string $prefix, array $bindValues)
    {
        $this->bindValues = array_values($bindValues);
    }

    /**
     * @return array<string, mixed>
     */
    public function getBindValues(): array
    {
        $result = [];

        foreach ($this->bindValues as $i => $value) {
            $result[$this->getPlaceholderByIndex($i)] = $value;
        }

        return $result;
    }

    public function getIn(): string
    {
        $result = [];
        $cnt = count($this->bindValues);

        for ($i = 0; $i < $cnt; $i++) {
            $result[] = $this->getPlaceholderByIndex($i);
        }

        return implode(', ', $result);
    }

    private function getPlaceholderByIndex(int $index): string
    {
        return ":{$this->prefix}_{$index}";
    }
}
