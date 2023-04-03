<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Helpers\Sqlite;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AutomapperColumn
{
    public const INT = "int";
    public const FLOAT = "float";
    public const BOOL = "bool";
    public const STRING = "string";
    public const DT = "\DateTime";
    public const JSON = "json";
    public const ENUM = "enum";
    public const CUSTOM = "custom";

    public function __construct(
        public readonly string $columnName,
        public readonly string $propertyMapper = self::STRING,
        public readonly mixed $propertyMapperArg = null,
    )
    {
    }
}
