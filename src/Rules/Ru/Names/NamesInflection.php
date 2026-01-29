<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru\Names;

abstract class NamesInflection implements Cases
{
    /**
     * @return array<string, string>
     */
    abstract public static function getCases(string $name, ?string $gender = null): array;

    abstract public static function getCase(string $name, string $case, ?string $gender = null): string;

    abstract public static function isMutable(string $name, ?string $gender = null): bool;

    abstract public static function detectGender(string $name): ?string;
}
