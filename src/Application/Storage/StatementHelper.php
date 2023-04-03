<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Storage;

class StatementHelper
{
    /**
     * @var string[]|array<string, string>
     */
    private array $joins = [];

    /**
     * @var bool
     */
    private bool $_isApplicable = true;

    /**
     * @var array|array<string, mixed>
     */
    private array $bindValues = [];

    /**
     * @var array
     */
    private array $whereArr = [];

    public static function implodeWhere(array $whereArr): string
    {
        return \implode(
            " ",
            \array_map(static function ($index) use ($whereArr) {
                return (($index) ? " " . $whereArr[$index][0] : "") . " (" . $whereArr[$index][1] . ") ";
            }, \array_keys($whereArr))
        );
    }

    public function getWhereStatement(): string
    {
        return self::implodeWhere($this->whereArr);
    }

    public function setWhereArray(array $whereArray): void
    {
        if (!empty($whereArray)) {
            $this->whereArr = $whereArray;
        }
    }

    /**
     * @param string|int|float $value
     * @noinspection PhpMissingParamTypeInspection
     */
    public function bind(string $placeholder, $value): void
    {
        if ($placeholder[0] !== ':') {
            $placeholder = ":{$placeholder}";
        }

        $this->bindValues[$placeholder] = $value;
    }

    public function bindAll(array $bindValues): void
    {
        foreach ($bindValues as $k => $v) {
            $this->bind($k, $v);
        }
    }

    public function getBindValues(): array
    {
        return $this->bindValues;
    }

    public function isValid(): bool
    {
        return $this->_isApplicable && !empty($this->whereArr);
    }

    public function setApplicable(bool $value): void
    {
        $this->_isApplicable = $value;
    }

    public function isApplicable(): bool
    {
        return $this->_isApplicable;
    }

    public function addJoin(string $tableName, string $joinStatement): void
    {
        $this->joins[$tableName] = $joinStatement;
    }

    public function getJoins(): array
    {
        return $this->joins;
    }
}
