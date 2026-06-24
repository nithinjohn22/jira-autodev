<?php

declare(strict_types=1);

namespace App\Utils;

class ErrorHandler
{
    public static function wrap(callable $fn, string $context = ''): mixed
    {
        try {
            return $fn();
        } catch (\Throwable $e) {
            $message = $context ? "[{$context}] {$e->getMessage()}" : $e->getMessage();
            throw new \RuntimeException($message, $e->getCode(), $e);
        }
    }

    public static function safe(callable $fn, mixed $default = null): mixed
    {
        try {
            return $fn();
        } catch (\Throwable) {
            return $default;
        }
    }
}
