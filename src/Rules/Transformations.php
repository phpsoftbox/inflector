<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules;

use function preg_replace;

/**
 * Набор трансформаций в порядке применения.
 */
final class Transformations
{
    /**
     * @var list<Transformation>
     */
    private array $transformations;

    public function __construct(Transformation ...$transformations)
    {
        $this->transformations = $transformations;
    }

    public function apply(string $word): string
    {
        foreach ($this->transformations as $t) {
            if ($t->pattern->matches($word)) {
                return (string) preg_replace($t->pattern->getRegex(), $t->replacement, $word);
            }
        }

        return $word;
    }
}
