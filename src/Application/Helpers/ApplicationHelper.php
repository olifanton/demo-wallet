<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Application\Helpers;

final class ApplicationHelper
{
    public static function getRootDirectory(): string
    {
        if (!defined("ROOT_DIR")) {
            throw new \RuntimeException("Application is misconfigured, `ROOT_PATH` constant is required");
        }

        return ROOT_DIR;
    }

    public static function getNs(): string
    {
        return "Olifanton\\DemoWallet";
    }
}
