<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules;

use function array_any;

/**
 * Набор паттернов для проверки "не склоняется".
 */
final class Patterns
{
    /**
     * @var list<Pattern>
     */
    private array $patterns;

    public function __construct(Pattern ...$patterns)
    {
        $this->patterns = $patterns;
    }

    public function isUninflected(string $word): bool
    {
        return array_any($this->patterns, fn ($pattern) => $pattern->matches($word));

    }
}
