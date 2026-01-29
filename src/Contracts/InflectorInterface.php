<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Contracts;

interface InflectorInterface
{
    public function pluralize(string $word): string;

    public function singularize(string $word): string;

    public function pluralizeByCount(int $count, string $one, string $few, string $many): string;

    /**
     * @return array<string, string>
     */
    public function getNameCases(string $fullName, ?string $gender = null): array;

    public function getNameCase(string $fullName, string $case, ?string $gender = null): string;

    public function detectNameGender(string $fullName): ?string;
}
