<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru\Names;

use function in_array;

final class Language
{
    /** @var list<string> */
    public static array $vowels = ['а', 'е', 'ё', 'и', 'о', 'у', 'ы', 'э', 'ю', 'я'];

    /** @var list<string> */
    public static array $consonants = ['б', 'в', 'г', 'д', 'ж', 'з', 'й', 'к', 'л', 'м', 'н', 'п', 'р', 'с', 'т', 'ф', 'х', 'ц', 'ч', 'ш', 'щ'];

    /** @var list<string> */
    public static array $sonorousConsonants = ['б', 'в', 'г', 'д', 'з', 'ж', 'л', 'м', 'н', 'р'];

    /** @var list<string> */
    public static array $deafConsonants = ['п', 'ф', 'к', 'т', 'с', 'ш', 'х', 'ч', 'щ'];

    public static function isConsonant(string $char): bool
    {
        return in_array(StringHelper::lower($char), self::$consonants, true);
    }

    public static function isVowel(string $char): bool
    {
        return in_array(StringHelper::lower($char), self::$vowels, true);
    }

    public static function isHissingConsonant(string $char): bool
    {
        return in_array(StringHelper::lower($char), ['ж', 'ш', 'ч', 'щ'], true);
    }

    public static function isSonorousConsonant(string $char): bool
    {
        return in_array(StringHelper::lower($char), self::$sonorousConsonants, true);
    }

    public static function isDeafConsonant(string $char): bool
    {
        return in_array(StringHelper::lower($char), self::$deafConsonants, true);
    }
}
