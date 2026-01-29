<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru;

use PhpSoftBox\Inflector\Inflector;

/**
 * Фабрика RU-правил.
 */
final class InflectorFactory
{
    public static function create(): Inflector
    {
        return new Inflector(
            pluralRuleset: Rules::getPluralRuleset(),
            singularRuleset: Rules::getSingularRuleset(),
            nameInflection: new NameInflection(),
        );
    }
}
