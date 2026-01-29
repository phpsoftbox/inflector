<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\En;

use PhpSoftBox\Inflector\Rules\Patterns;
use PhpSoftBox\Inflector\Rules\Substitutions;
use PhpSoftBox\Inflector\Rules\Transformations;
use PhpSoftBox\Inflector\Ruleset;

/**
 * Английские правила.
 *
 * По аналогии с Doctrine Inflector:
 * - отдельный ruleset для plural
 * - отдельный ruleset для singular
 */
final class Rules
{
    public static function getSingularRuleset(): Ruleset
    {
        return new Ruleset(
            new Transformations(...Inflectable::getSingular()),
            new Patterns(...Uninflected::getSingular()),
            new Substitutions(...Inflectable::getIrregular())->getFlippedSubstitutions(),
        );
    }

    public static function getPluralRuleset(): Ruleset
    {
        return new Ruleset(
            new Transformations(...Inflectable::getPlural()),
            new Patterns(...Uninflected::getPlural()),
            new Substitutions(...Inflectable::getIrregular()),
        );
    }
}
