<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru\Names;

use function function_exists;
use function mb_strlen;
use function mb_strtolower;
use function mb_strtoupper;
use function mb_substr;
use function preg_replace;
use function strlen;
use function strtolower;
use function strtoupper;
use function substr;
use function trim;

final class StringHelper
{
    public static function lower(string $value): string
    {
        return function_exists('mb_strtolower') ? mb_strtolower($value) : strtolower($value);
    }

    public static function upper(string $value): string
    {
        return function_exists('mb_strtoupper') ? mb_strtoupper($value) : strtoupper($value);
    }

    public static function length(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value) : strlen($value);
    }

    public static function slice(string $value, int $start, ?int $length = null): string
    {
        if (function_exists('mb_substr')) {
            return $length === null ? mb_substr($value, $start) : mb_substr($value, $start, $length);
        }

        return $length === null ? substr($value, $start) : substr($value, $start, $length);
    }

    public static function title(string $value): string
    {
        $lower = self::lower($value);
        $first = self::upper(self::slice($lower, 0, 1));

        return $first . self::slice($lower, 1);
    }

    public static function normalizeSpaces(string $value): string
    {
        $trimmed    = trim($value);
        $normalized = preg_replace('/\s{2,}/u', ' ', $trimmed);

        return $normalized ?? $trimmed;
    }
}
