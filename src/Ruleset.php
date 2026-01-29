<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector;

use PhpSoftBox\Inflector\Rules\Patterns;
use PhpSoftBox\Inflector\Rules\Substitutions;
use PhpSoftBox\Inflector\Rules\Transformations;

/**
 * Набор правил (ruleset) для конкретного языка.
 */
final readonly class Ruleset
{
    public function __construct(
        private Transformations $regular,
        private Patterns $uninflected,
        private Substitutions $irregular,
    ) {
    }

    public function getRegular(): Transformations
    {
        return $this->regular;
    }

    public function getUninflected(): Patterns
    {
        return $this->uninflected;
    }

    public function getIrregular(): Substitutions
    {
        return $this->irregular;
    }
}
