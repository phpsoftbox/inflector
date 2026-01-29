<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Contracts;

interface NameInflectionInterface
{
    /**
     * @return array<string, string>
     */
    public function getCases(string $fullName, ?string $gender = null): array;

    public function getCase(string $fullName, string $case, ?string $gender = null): string;

    public function detectGender(string $fullName): ?string;
}
