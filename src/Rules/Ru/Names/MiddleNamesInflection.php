<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru\Names;

use PhpSoftBox\Inflector\Names\Gender;

use function in_array;

final class MiddleNamesInflection extends NamesInflection
{
    public static function detectGender(string $name): ?string
    {
        $name = StringHelper::lower($name);
        if (StringHelper::slice($name, -2) === 'ич') {
            return Gender::MALE->value;
        }
        if (StringHelper::slice($name, -2) === 'на') {
            return Gender::FEMALE->value;
        }

        return null;
    }

    public static function isMutable(string $name, ?string $gender = null): bool
    {
        if (StringHelper::length($name) === 1) {
            return false;
        }

        $name = StringHelper::lower($name);

        if (in_array(StringHelper::slice($name, -2), ['ич', 'на'], true)) {
            return true;
        }

        return FirstNamesInflection::isMutable($name, $gender);
    }

    public static function getCase(string $name, string $case, ?string $gender = null): string
    {
        $forms = self::getCases($name, $gender);
        $case  = CasesHelper::canonize($case);

        return $forms[$case] ?? $name;
    }

    /**
     * @return array<string, string>
     */
    public static function getCases(string $name, ?string $gender = null): array
    {
        $name = StringHelper::lower($name);

        if (StringHelper::slice($name, -2) === 'ич') {
            $name = StringHelper::title($name);

            return [
                self::IMENIT  => $name,
                self::RODIT   => $name . 'а',
                self::DAT     => $name . 'у',
                self::VINIT   => $name . 'а',
                self::TVORIT  => $name . 'ем',
                self::PREDLOJ => $name . 'е',
            ];
        }

        if (StringHelper::slice($name, -2) === 'на') {
            $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

            return [
                self::IMENIT  => $prefix . 'а',
                self::RODIT   => $prefix . 'ы',
                self::DAT     => $prefix . 'е',
                self::VINIT   => $prefix . 'у',
                self::TVORIT  => $prefix . 'ой',
                self::PREDLOJ => $prefix . 'е',
            ];
        }

        return FirstNamesInflection::getCases($name, $gender);
    }
}
