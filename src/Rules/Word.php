<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules;

/**
 * Обёртка над словом.
 *
 * Нужна, чтобы rule-объекты (Substitution) выглядели как в Doctrine.
 */
final readonly class Word
{
    public function __construct(
        public string
    $word)
    {
    }
}
