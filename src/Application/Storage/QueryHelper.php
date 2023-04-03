<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Storage;

use Olifanton\DemoWallet\Application\Helpers\Sanitizer;

class QueryHelper
{
    public static function attachJoin(string $sql, StatementHelper $where, ?array &$bindValues = null): string
    {
        if (!empty($where->getJoins())) {
            $sql .= (" " . \implode("\n", $where->getJoins()));

            if ($bindValues) {
                foreach ($where->getBindValues() as $placeholder => $value) {
                    $bindValues[$placeholder] = $value;
                }
            }
        }

        return $sql;
    }

    public static function attachWhere(string $sql, StatementHelper $where, ?array &$bindValues = null): string
    {
        if ($where->isValid()) {
            $sql .= " WHERE {$where->getWhereStatement()}";

            if ($bindValues) {
                foreach ($where->getBindValues() as $placeholder => $value) {
                    $bindValues[$placeholder] = $value;
                }
            }
        }

        return $sql;
    }

    /**
     * @param int[]|null $values
     */
    public static function getInInt(?array $values): ?string
    {
        if (is_array($values)) {
            $values = Sanitizer::sanitizeIntArray($values, true);

            if (!empty($values)) {
                return self::implodeValues($values);
            }
        }

        return null;
    }

    public static function getInPlaceholders(?array $values, string $prefix): ?InPlaceholder
    {
        if (is_array($values) && !empty($values)) {
            return new InPlaceholder(
                $prefix,
                array_map(static function ($v) {
                    if ($v instanceof \BackedEnum) {
                        return $v->value;
                    }

                    return $v;
                }, $values),
            );
        }

        return null;
    }

    public static function likeEscape(string $str): string
    {
        $escapeChar = '\\';

        return str_replace(
            [
                $escapeChar,
                '%',
                '_',
            ],
            [
                $escapeChar . $escapeChar,
                $escapeChar . '%',
                $escapeChar . '_',
            ],
            $str,
        );
    }

    /**
     * @param array $values
     * @return string
     */
    private static function implodeValues(array $values): string
    {
        return "'" . \implode("','", $values) . "'";
    }
}
