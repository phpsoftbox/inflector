<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules;

use function function_exists;
use function mb_strtolower;
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
            $map[$this->toLower($substitution->from->word)] = $substitution->to->word;
        }

        $this->map = $map;
    }

    public function get(string $word): ?string
    {
        return $this->map[$this->toLower($word)] ?? null;
    }

    public function getFlippedSubstitutions(): self
    {
        $flipped = [];
        foreach ($this->map as $from => $to) {
            $flipped[] = new Substitution(new Word($to), new Word($from));
        }

        return new self(...$flipped);
    }

    private function toLower(string $value): string
    {
        return function_exists('mb_strtolower') ? mb_strtolower($value) : strtolower($value);
    }
}
