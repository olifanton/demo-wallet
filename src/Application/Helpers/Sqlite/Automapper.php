<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Helpers\Sqlite;

use Olifanton\DemoWallet\Application\Storage\CustomDeserializer;

/**
 * @template T
 */
class Automapper
{
    /**
     * @var array<class-string, \ReflectionClass>
     */
    private static array $reflections = [];

    /**
     * @var array<class-string, array<string, string>>
     */
    private static array $maps = [];

    /**
     * @param class-string<T> $modelClass
     */
    public function __construct(
        private readonly string $modelClass,
    )
    {
    }

    /**
     * @param array $row
     * @return T
     * @throws \Throwable
     */
    public function map(array $row)
    {
        $reflection = self::ensureReflectionClass($this->modelClass);
        $map = self::ensureMap($this->modelClass, $reflection);
        $instance = $reflection->newInstance();

        if (!empty($map)) {
            $propsMap = [];
            $allProps = $reflection->getProperties();

            foreach ($allProps as $property) {
                $propsMap[$property->getName()] = $property;
            }

            foreach ($row as $col => $value) {
                if (isset($map[$col])) {
                    $propertyName = $map[$col]["propertyName"];
                    $property = $propsMap[$propertyName];

                    if ($value === null && $property->getType()?->allowsNull()) {
                        continue;
                    }

                    $isProtected = ($property->isPrivate() || $property->isProtected());

                    if ($isProtected) {
                        $propsMap[$propertyName]->setAccessible(true);
                    }

                    $propertyMapper = $map[$col]["propertyMapper"];
                    $propertyMapperArg = $map[$col]["propertyMapperArg"];

                    $value = match ($propertyMapper) {
                        AutomapperColumn::BOOL => (bool)$value,
                        AutomapperColumn::INT => (int)$value,
                        AutomapperColumn::FLOAT => (float)$value,
                        AutomapperColumn::STRING => (string)$value,
                        AutomapperColumn::DT => self::deserializeDatetime($value),
                        AutomapperColumn::JSON => ($value) ? self::deserializeJson((string)$value) : null,
                        AutomapperColumn::ENUM => ($value) ? call_user_func_array([$propertyMapperArg, "tryFrom"], [$value]): null,
                        AutomapperColumn::CUSTOM => self::deserializeCustom($value, $propertyMapperArg),
                        default => throw new \RuntimeException(
                            "Unknown mapper \"$propertyMapper\", property \"$propertyName\", class \"$this->modelClass\""
                        ),
                    };

                    $property->setValue($instance, $value);

                    if ($isProtected) {
                        $propsMap[$propertyName]->setAccessible(false);
                    }
                }
            }
        }

        return $instance;
    }

    private static function deserializeDatetime(string $dateTimeString): ?\DateTimeInterface
    {
        $tryDt = \DateTimeImmutable::createFromFormat("Y-m-d H:i:s", $dateTimeString);

        if ($tryDt) {
            return $tryDt;
        }

        return null;
    }

    /**
     * @throws \JsonException
     */
    private static function deserializeJson(?string $value): ?array
    {
        if (is_string($value)) {
            return json_decode($value, true, 128, JSON_THROW_ON_ERROR);
        }

        return null;
    }

    private static function deserializeCustom(string $value, string $customDeserializer)
    {
        if (!is_subclass_of($customDeserializer, CustomDeserializer::class)) {
            throw new \InvalidArgumentException();
        }

        return call_user_func([$customDeserializer, "deserialize"], $value);
    }

    /**
     * @param class-string $class
     * @throws \ReflectionException
     */
    private static function ensureReflectionClass(string $class): \ReflectionClass
    {
        if (!isset(self::$reflections[$class])) {
            self::$reflections[$class] = new \ReflectionClass($class);
        }

        return self::$reflections[$class];
    }

    /**
     * @return array<string, array{propertyName: string, propertyMapper: string, propertyMapperArg: mixed}>
     */
    private static function ensureMap(string $class, \ReflectionClass $reflection): array
    {
        if (!isset(self::$maps[$class])) {
            $map = [];
            $reflectionProperties = $reflection->getProperties();

            foreach ($reflectionProperties as $reflectionProperty) {
                $mapColAttrs = $reflectionProperty->getAttributes(AutomapperColumn::class);

                if (!empty($mapColAttrs)) {
                    $propertyName = $reflectionProperty->getName();
                    $mapColAttr = $mapColAttrs[0];
                    $mysqlColumnName = $mapColAttr->getArguments()[0] ?? "";
                    $propertyMapper = $mapColAttr->getArguments()[1] ?? AutomapperColumn::STRING;
                    $propertyMapperArg = $mapColAttr->getArguments()[2] ?? null;
                    $mysqlColumnName = trim($mysqlColumnName);

                    if (empty($mysqlColumnName)) {
                        throw new \RuntimeException(
                            sprintf(
                                "Invalid argument for AutomapColumn attribute, class: \"%s\", property: \"%s\"",
                                $class,
                                $propertyName,
                            ),
                        );
                    }

                    $map[$mysqlColumnName] = [
                        "propertyName" => $propertyName,
                        "propertyMapper" => $propertyMapper,
                        "propertyMapperArg" => $propertyMapperArg,
                    ];
                }
            }

            self::$maps[$class] = $map;
        }

        return self::$maps[$class];
    }
}
