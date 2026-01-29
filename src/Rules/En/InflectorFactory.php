<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\En;

use PhpSoftBox\Inflector\Inflector;

/**
 * Фабрика EN-правил.
 */
final class InflectorFactory
{
    public static function create(): Inflector
    {
        return new Inflector(
            pluralRuleset: Rules::getPluralRuleset(),
            singularRuleset: Rules::getSingularRuleset(),
        );
    }
}
