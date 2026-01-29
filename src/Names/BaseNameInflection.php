<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Names;

use PhpSoftBox\Inflector\Contracts\NameInflectionInterface;

class BaseNameInflection implements NameInflectionInterface
{
    /**
     * @return array<string, string>
     */
    public function getCases(string $fullName, ?string $gender = null): array
    {
        return [
            Cases::NOMINATIVE->value    => $fullName,
            Cases::GENITIVE->value      => $fullName,
            Cases::DATIVE->value        => $fullName,
            Cases::ACCUSATIVE->value    => $fullName,
            Cases::ABLATIVE->value      => $fullName,
            Cases::PREPOSITIONAL->value => $fullName,
        ];
    }

    public function getCase(string $fullName, string $case, ?string $gender = null): string
    {
        return $fullName;
    }

    public function detectGender(string $fullName): ?string
    {
        return null;
    }
}
