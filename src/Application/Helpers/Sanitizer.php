<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Helpers;

final class Sanitizer
{
    public static function sanitizeIntArray(array $ints, bool $uniqueOnly = true): array
    {
        $result = [];

        foreach ($ints as $int) {
            if (is_numeric($int)) {
                $result[] = (int)$int;
            }
        }

        if ($uniqueOnly) {
            $result = \array_unique($result);
        }

        return \array_values($result);
    }
}
