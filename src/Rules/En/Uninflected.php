<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\En;

use PhpSoftBox\Inflector\Rules\Pattern;

/**
 * Uninflected (слова/паттерны, которые не склоняются).
 *
 * Можно задавать как строки, так и регулярные выражения.
 */
final class Uninflected
{
    /**
     * @return iterable<Pattern>
     */
    public static function getSingular(): iterable
    {
        yield from self::getDefault();

        // singular-specific
        yield new Pattern('.*ss');
        yield new Pattern('clothes');
        yield new Pattern('data');
        yield new Pattern('fascia');
        yield new Pattern('fuchsia');
        yield new Pattern('galleria');
        yield new Pattern('mafia');
        yield new Pattern('militia');
        yield new Pattern('pants');
        yield new Pattern('petunia');
        yield new Pattern('sepia');
        yield new Pattern('trivia');
        yield new Pattern('utopia');
    }

    /**
     * @return iterable<Pattern>
     */
    public static function getPlural(): iterable
    {
        yield from self::getDefault();

        // plural-specific
        yield new Pattern('people');
        yield new Pattern('trivia');
        yield new Pattern('\\w+ware$');
        yield new Pattern('media');
    }

    /**
     * Базовый набор слов/паттернов, которые не склоняются.
     *
     * @return iterable<Pattern>
     */
    private static function getDefault(): iterable
    {
        // regex, как в Doctrine: "\w+media" (metadata, socialmedia, etc.)
        yield new Pattern('\\w+media');

        yield new Pattern('advice');
        yield new Pattern('aircraft');
        yield new Pattern('art');
        yield new Pattern('audio');
        yield new Pattern('baggage');
        yield new Pattern('bison');
        yield new Pattern('butter');
        yield new Pattern('carp');
        yield new Pattern('cattle');
        yield new Pattern('chassis');
        yield new Pattern('clippers');
        yield new Pattern('clothing');
        yield new Pattern('coal');
        yield new Pattern('cod');
        yield new Pattern('compensation');
        yield new Pattern('corps');
        yield new Pattern('cotton');
        yield new Pattern('data');
        yield new Pattern('debris');
        yield new Pattern('deer');
        yield new Pattern('diabetes');
        yield new Pattern('education');
        yield new Pattern('elk');
        yield new Pattern('emoji');
        yield new Pattern('equipment');
        yield new Pattern('evidence');
        yield new Pattern('feedback');
        yield new Pattern('fish');
        yield new Pattern('flour');
        yield new Pattern('food');
        yield new Pattern('furniture');
        yield new Pattern('gallows');
        yield new Pattern('gold');
        yield new Pattern('headquarters');
        yield new Pattern('herpes');
        yield new Pattern('hijinks');
        yield new Pattern('homework');
        yield new Pattern('information');
        yield new Pattern('jeans');
        yield new Pattern('jedi');
        yield new Pattern('kin');
        yield new Pattern('knowledge');
        yield new Pattern('leather');
        yield new Pattern('love');
        yield new Pattern('luggage');
        yield new Pattern('mackerel');
        yield new Pattern('management');
        yield new Pattern('metadata');
        yield new Pattern('mews');
        yield new Pattern('money');
        yield new Pattern('moose');
        yield new Pattern('mumps');
        yield new Pattern('music');
        yield new Pattern('news');
        yield new Pattern('nutrition');
        yield new Pattern('offspring');
        yield new Pattern('oil');
        yield new Pattern('patience');
        yield new Pattern('plankton');
        yield new Pattern('pliers');
        yield new Pattern('pokemon');
        yield new Pattern('police');
        yield new Pattern('rain');
        yield new Pattern('research');
        yield new Pattern('rice');
        yield new Pattern('salmon');
        yield new Pattern('sand');
        yield new Pattern('scissors');
        yield new Pattern('series');
        yield new Pattern('shears');
        yield new Pattern('sheep');
        yield new Pattern('sms');
        yield new Pattern('soap');
        yield new Pattern('spam');
        yield new Pattern('species');
        yield new Pattern('staff');
        yield new Pattern('sugar');
        yield new Pattern('swine');
        yield new Pattern('talent');
        yield new Pattern('toothpaste');
        yield new Pattern('traffic');
        yield new Pattern('travel');
        yield new Pattern('trousers');
        yield new Pattern('tuna');
        yield new Pattern('us');
        yield new Pattern('weather');
        yield new Pattern('wheat');
        yield new Pattern('wood');
        yield new Pattern('wool');
    }
}
