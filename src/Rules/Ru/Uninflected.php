<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru;

use PhpSoftBox\Inflector\Rules\Pattern;

/**
 * Uninflected для русского языка.
 */
final class Uninflected
{
    /**
     * @return iterable<Pattern>
     */
    public static function getSingular(): iterable
    {
        yield from self::getDefault();
    }

    /**
     * @return iterable<Pattern>
     */
    public static function getPlural(): iterable
    {
        yield from self::getDefault();
    }

    /**
     * @return iterable<Pattern>
     */
    private static function getDefault(): iterable
    {
        // Набор можно расширять по мере появления слов, не меняющих форму.
        yield new Pattern('/^киз$/iu');
        yield new Pattern('/^sms$/iu');
        yield new Pattern('/^api$/iu');
    }
}
