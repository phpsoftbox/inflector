<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru;

use PhpSoftBox\Inflector\Contracts\NameInflectionInterface;
use PhpSoftBox\Inflector\Names\Cases;
use PhpSoftBox\Inflector\Names\Gender;
use PhpSoftBox\Inflector\Rules\Ru\Names\CasesHelper;
use PhpSoftBox\Inflector\Rules\Ru\Names\FirstNamesInflection;
use PhpSoftBox\Inflector\Rules\Ru\Names\LastNamesInflection;
use PhpSoftBox\Inflector\Rules\Ru\Names\MiddleNamesInflection;
use PhpSoftBox\Inflector\Rules\Ru\Names\StringHelper;

use function array_fill_keys;
use function count;
use function explode;

final class NameInflection implements NameInflectionInterface
{
    /**
     * @return array<value-of<Cases>, string>
     */
    public function getCases(string $fullName, ?Gender $gender = null): array
    {
        $normalized = $this->normalizeFullName($fullName);
        if ($normalized === '') {
            return [];
        }

        $genderValue = ($gender ?? $this->detectGender($normalized))?->value;
        $parts       = explode(' ', $normalized);

        return match (count($parts)) {
            1       => FirstNamesInflection::getCases($parts[0], $genderValue),
            2       => $this->composeTwoParts($parts, $genderValue),
            3       => $this->composeThreeParts($parts, $genderValue),
            default => array_fill_keys(CasesHelper::allCases(), $normalized),
        };
    }

    public function getCase(string $fullName, Cases $case, ?Gender $gender = null): string
    {
        $normalized = $this->normalizeFullName($fullName);
        if ($normalized === '') {
            return '';
        }

        return $this->getCases($normalized, $gender)[$case->value] ?? $normalized;
    }

    public function detectGender(string $fullName): ?Gender
    {
        $normalized = $this->normalizeFullName($fullName);
        if ($normalized === '') {
            return null;
        }

        $parts = explode(' ', StringHelper::lower($normalized));

        $gender = match (count($parts)) {
            1 => FirstNamesInflection::detectGender($parts[0]),
            2 => LastNamesInflection::detectGender($parts[0]) ?: FirstNamesInflection::detectGender($parts[1]),
            3 => MiddleNamesInflection::detectGender($parts[2])
                ?: (LastNamesInflection::detectGender($parts[0]) ?: FirstNamesInflection::detectGender($parts[1])),
            default => null,
        };

        return $gender === null ? null : Gender::from($gender);
    }

    private function normalizeFullName(string $fullName): string
    {
        return StringHelper::normalizeSpaces($fullName);
    }

    /**
     * @param list<string> $parts
     * @return array<string, string>
     */
    private function composeTwoParts(array $parts, ?string $gender): array
    {
        $part1 = LastNamesInflection::getCases($parts[0], $gender);
        $part2 = FirstNamesInflection::getCases($parts[1], $gender);

        return CasesHelper::composeCasesFromWords([$part1, $part2]);
    }

    /**
     * @param list<string> $parts
     * @return array<string, string>
     */
    private function composeThreeParts(array $parts, ?string $gender): array
    {
        $part1 = LastNamesInflection::getCases($parts[0], $gender);
        $part2 = FirstNamesInflection::getCases($parts[1], $gender);
        $part3 = MiddleNamesInflection::getCases($parts[2], $gender);

        return CasesHelper::composeCasesFromWords([$part1, $part2, $part3]);
    }
}
