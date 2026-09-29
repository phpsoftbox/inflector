<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru;

use PhpSoftBox\Inflector\Rules\Pattern;

/**
 * Uninflected для русского языка: несклоняемые заимствования, аббревиатуры и слова без единственного числа.
 */
final class Uninflected
{
    /**
     * @return iterable<Pattern>
     */
    public static function getSingular(): iterable
    {
        yield from self::getDefault();

        // Только множественное число
        yield new Pattern('/^(деньги|сутки|ножницы|брюки|очки|данные|часы)$/iu');
    }

    /**
     * @return iterable<Pattern>
     */
    public static function getPlural(): iterable
    {
        yield from self::getDefault();

        yield new Pattern('/^(деньги|сутки|ножницы|брюки|очки|данные)$/iu');
    }

    /**
     * @return iterable<Pattern>
     */
    private static function getDefault(): iterable
    {
        // Аббревиатуры и латиница
        yield new Pattern('/^(киз|инн|кпп|огрн|бик|ндс|sms|api)$/iu');
        yield new Pattern('/^[a-z0-9_-]+$/iu');

        // Несклоняемые заимствования
        yield new Pattern('/^(кофе|кафе|какао|пальто|метро|кино|такси|меню|шоссе|жюри|интервью|резюме|радио|фото|видео|авто|депо|кашпо|пюре|кенгуру|евро)$/iu');
    }
}
