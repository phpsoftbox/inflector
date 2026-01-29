<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules;

use function strtolower;

/**
 * Набор подстановок (irregular).
 */
final class Substitutions
{
    /**
     * @var array<string, string>
     */
    private array $map;

    public function __construct(Substitution ...$substitutions)
    {
        $map = [];
        foreach ($substitutions as $substitution) {
            $map[strtolower($substitution->from->word)] = $substitution->to->word;
        }

        $this->map = $map;
    }

    public function get(string $word): ?string
    {
        return $this->map[strtolower($word)] ?? null;
    }

    public function getFlippedSubstitutions(): self
    {
        $flipped = [];
        foreach ($this->map as $from => $to) {
            $flipped[] = new Substitution(new Word($to), new Word($from));
        }

        return new self(...$flipped);
    }
}
