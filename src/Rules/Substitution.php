<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules;

/**
 * Substitution (irregular): замена одного слова на другое.
 */
final readonly class Substitution
{
    public function __construct(
        public Word $from,
        public Word $to,
    ) {
    }
}
