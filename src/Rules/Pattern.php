<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules;

use function preg_match;
use function preg_quote;
use function strpbrk;

/**
 * Pattern для Patterns.
 *
 * В отличие от простых строк, Pattern может быть:
 * - готовой регуляркой ("/.../")
 * - строкой, которая автоматически будет превращена в "/.../i"
 */
final class Pattern
{
    private string $pattern;
    private string $regex;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;

        if (isset($this->pattern[0]) && $this->pattern[0] === '/') {
            $this->regex = $this->pattern;

            return;
        }

        // Если это «простое слово» без regex-метасимволов — матчим строго целиком.
        // Это важно, чтобы паттерн "us" не совпадал с "bus".
        if ($this->isPlainWord($this->pattern)) {
            $quoted      = preg_quote($this->pattern, '/');
            $this->regex = '/^' . $quoted . '$/i';

            return;
        }

        // Иначе считаем, что передан regex-фрагмент и оборачиваем в /.../i
        $this->regex = '/' . $this->pattern . '/i';
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getRegex(): string
    {
        return $this->regex;
    }

    public function matches(string $word): bool
    {
        return preg_match($this->getRegex(), $word) === 1;
    }

    private function isPlainWord(string $pattern): bool
    {
        // Любой regex-метасимвол делает паттерн «не простым словом»
        return strpbrk($pattern, '\\[](){}.+*?^$|') === false;
    }
}
