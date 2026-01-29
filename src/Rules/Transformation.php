<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules;

/**
 * Transformation: если Pattern совпал, подставляем replacement.
 */
final readonly class Transformation
{
    public function __construct(
        public Pattern $pattern,
        public string $replacement,
    ) {
    }
}
