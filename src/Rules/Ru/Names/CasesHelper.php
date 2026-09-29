<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru\Names;

use PhpSoftBox\Inflector\Names\Cases as BaseCases;

use function implode;

final class CasesHelper
{
    /**
     * Каноническое значение падежа по названию/сокращению; неизвестное — InvalidArgumentException.
     */
    public static function canonize(string $case): string
    {
        return BaseCases::fromAlias($case)->value;
    }

    /**
     * @param list<array<string, string>> $words
     * @return array<string, string>
     */
    public static function composeCasesFromWords(array $words, string $delimiter = ' '): array
    {
        $cases = [];
        foreach (self::allCases() as $case) {
            $fragments = [];
            foreach ($words as $wordCases) {
                $fragments[] = $wordCases[$case] ?? '';
            }
            $cases[$case] = implode($delimiter, $fragments);
        }

        return $cases;
    }

    /**
     * @return list<string>
     */
    public static function allCases(): array
    {
        return [
            Cases::IMENIT,
            Cases::RODIT,
            Cases::DAT,
            Cases::VINIT,
            Cases::TVORIT,
            Cases::PREDLOJ,
        ];
    }
}
