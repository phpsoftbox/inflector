<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Contracts;

use PhpSoftBox\Inflector\Names\Cases;
use PhpSoftBox\Inflector\Names\Gender;

interface NameInflectionInterface
{
    /**
     * @return array<value-of<Cases>, string>
     */
    public function getCases(string $fullName, ?Gender $gender = null): array;

    public function getCase(string $fullName, Cases $case, ?Gender $gender = null): string;

    public function detectGender(string $fullName): ?Gender;
}
