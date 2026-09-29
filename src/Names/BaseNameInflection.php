<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Names;

use PhpSoftBox\Inflector\Contracts\NameInflectionInterface;

use function array_fill_keys;

/**
 * Заглушка для языков без склонения ФИО: имя во всех падежах совпадает с исходным, пол не определяется.
 */
class BaseNameInflection implements NameInflectionInterface
{
    /**
     * @return array<value-of<Cases>, string>
     */
    public function getCases(string $fullName, ?Gender $gender = null): array
    {
        return array_fill_keys(Cases::values(), $fullName);
    }

    public function getCase(string $fullName, Cases $case, ?Gender $gender = null): string
    {
        return $fullName;
    }

    public function detectGender(string $fullName): ?Gender
    {
        return null;
    }
}
