<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru\Names;

use InvalidArgumentException;

use function implode;

final class CasesHelper
{
    public static function canonize(string $case): string
    {
        $case = StringHelper::lower($case);

        return match ($case) {
            Cases::IMENIT, 'именительный', 'именит', 'и', 'n', 'nominative' => Cases::IMENIT,
            Cases::RODIT, 'родительный', 'родит', 'р', 'g', 'genitive', 'genetive' => Cases::RODIT,
            Cases::DAT, 'дательный', 'дат', 'д', 'd', 'dative' => Cases::DAT,
            Cases::VINIT, 'винительный', 'винит', 'в', 'accusative' => Cases::VINIT,
            Cases::TVORIT, 'творительный', 'творит', 'т', 'a', 'ablative' => Cases::TVORIT,
            Cases::PREDLOJ, 'предложный', 'предлож', 'п', 'prepositional' => Cases::PREDLOJ,
            default => throw new InvalidArgumentException('Invalid case: ' . $case),
        };
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
