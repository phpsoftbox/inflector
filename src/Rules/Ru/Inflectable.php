<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru;

use PhpSoftBox\Inflector\Rules\Pattern;
use PhpSoftBox\Inflector\Rules\Substitution;
use PhpSoftBox\Inflector\Rules\Transformation;
use PhpSoftBox\Inflector\Rules\Word;

/**
 * Inflectable для русского языка.
 *
 * Правила намеренно консервативные: покрывают самые частые доменные слова.
 */
final class Inflectable
{
    /**
     * @return iterable<Transformation>
     */
    public static function getPlural(): iterable
    {
        // товар -> товары
        yield new Transformation(new Pattern('/([бвгджзйклмнпрстфхцчшщ])$/u'), '$1ы');

        // позиция -> позиции, компания -> компании
        yield new Transformation(new Pattern('/ия$/u'), 'ии');

        // модель -> модели
        yield new Transformation(new Pattern('/ь$/u'), 'и');
    }

    /**
     * @return iterable<Transformation>
     */
    public static function getSingular(): iterable
    {
        // товары -> товар
        yield new Transformation(new Pattern('/([бвгджзйклмнпрстфхцчшщ])ы$/u'), '$1');

        // позиции -> позиция, компании -> компания
        yield new Transformation(new Pattern('/ии$/u'), 'ия');

        // модели -> модель
        yield new Transformation(new Pattern('/и$/u'), 'ь');
    }

    /**
     * @return iterable<Substitution>
     */
    public static function getIrregular(): iterable
    {
        // Частые доменные слова, чтобы снять неоднозначность общих regex-правил.
        yield new Substitution(new Word('миграция'), new Word('миграции'));
        yield new Substitution(new Word('категория'), new Word('категории'));
        yield new Substitution(new Word('компания'), new Word('компании'));
        yield new Substitution(new Word('операция'), new Word('операции'));
        yield new Substitution(new Word('позиция'), new Word('позиции'));
        yield new Substitution(new Word('товар'), new Word('товары'));
        yield new Substitution(new Word('модель'), new Word('модели'));
    }
}
