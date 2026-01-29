<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\En;

use PhpSoftBox\Inflector\Rules\Pattern;
use PhpSoftBox\Inflector\Rules\Substitution;
use PhpSoftBox\Inflector\Rules\Transformation;
use PhpSoftBox\Inflector\Rules\Word;

/**
 * Inflectable (правила, которые реально склоняются).
 */
final class Inflectable
{
    /**
     * @return iterable<Transformation>
     */
    public static function getPlural(): iterable
    {
        yield new Transformation(new Pattern('(s)tatus$'), '$1tatuses');
        yield new Transformation(new Pattern('(c)ampus$'), '$1ampuses');
        yield new Transformation(new Pattern('^(.*)(menu)$'), '$1$2s');

        yield new Transformation(new Pattern('(quiz)$'), '$1zes');
        yield new Transformation(new Pattern('^(ox)$'), '$1en');
        yield new Transformation(new Pattern('([m|l])ouse$'), '$1ice');
        yield new Transformation(new Pattern('(matr|vert|ind)(ix|ex)$'), '$1ices');
        yield new Transformation(new Pattern('(x|ch|ss|sh)$'), '$1es');

        yield new Transformation(new Pattern('([^aeiouy]|qu)y$'), '$1ies');
        yield new Transformation(new Pattern('(hive|gulf)$'), '$1s');

        yield new Transformation(new Pattern('(?:([^f])fe|([lr])f)$'), '$1$2ves');

        yield new Transformation(new Pattern('sis$'), 'ses');
        yield new Transformation(new Pattern('([ti])um$'), '$1a');
        yield new Transformation(new Pattern('(tax)on$'), '$1a');
        yield new Transformation(new Pattern('(c)riterion$'), '$1riteria');

        yield new Transformation(new Pattern('(buffal|her|potat|tomat|volcan)o$'), '$1oes');
        yield new Transformation(new Pattern('(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|vir)us$'), '$1i');

        // bus -> buses
        yield new Transformation(new Pattern('(bu)s$'), '$1ses');

        yield new Transformation(new Pattern('(alias)$'), '$1es');
        yield new Transformation(new Pattern('(analys|ax|cris|test|thes)is$'), '$1es');

        yield new Transformation(new Pattern('s$'), 's');
        yield new Transformation(new Pattern('^$'), '');
        yield new Transformation(new Pattern('$'), 's');
    }

    /**
     * @return iterable<Transformation>
     */
    public static function getSingular(): iterable
    {
        yield new Transformation(new Pattern('(s)tatuses$'), '$1tatus');
        yield new Transformation(new Pattern('(s)tatus$'), '$1tatus');
        yield new Transformation(new Pattern('(c)ampuses$'), '$1ampus');
        yield new Transformation(new Pattern('^(.*)(menu)s$'), '$1$2');

        yield new Transformation(new Pattern('(quiz)zes$'), '$1');
        yield new Transformation(new Pattern('(matr)ices$'), '$1ix');
        yield new Transformation(new Pattern('(vert|ind)ices$'), '$1ex');
        yield new Transformation(new Pattern('^(ox)en$'), '$1');

        yield new Transformation(new Pattern('(alias)(es)*$'), '$1');
        yield new Transformation(new Pattern('(buffal|her|potat|tomat|volcan)oes$'), '$1o');
        yield new Transformation(new Pattern('(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$'), '$1us');

        yield new Transformation(new Pattern('(analys|ax|cris|test|thes)es$'), '$1is');
        yield new Transformation(new Pattern('(shoe|slave)s$'), '$1');
        yield new Transformation(new Pattern('(o)es$'), '$1');

        yield new Transformation(new Pattern('ouses$'), 'ouse');
        yield new Transformation(new Pattern('([^a])uses$'), '$1us');

        yield new Transformation(new Pattern('([m|l])ice$'), '$1ouse');
        yield new Transformation(new Pattern('(x|ch|ss|sh)es$'), '$1');

        yield new Transformation(new Pattern('([^aeiouy]|qu)ies$'), '$1y');
        yield new Transformation(new Pattern('([lr])ves$'), '$1f');
        yield new Transformation(new Pattern('([^fo])ves$'), '$1fe');

        yield new Transformation(new Pattern('(tax)a$'), '$1on');
        yield new Transformation(new Pattern('(c)riteria$'), '$1riterion');
        yield new Transformation(new Pattern('([ti])a$'), '$1um');

        yield new Transformation(new Pattern('s$'), '');
    }

    /**
     * @return iterable<Substitution>
     */
    public static function getIrregular(): iterable
    {
        // базовые
        yield new Substitution(new Word('person'), new Word('people'));
        yield new Substitution(new Word('man'), new Word('men'));
        yield new Substitution(new Word('woman'), new Word('women'));
        yield new Substitution(new Word('child'), new Word('children'));
        yield new Substitution(new Word('tooth'), new Word('teeth'));
        yield new Substitution(new Word('foot'), new Word('feet'));
        yield new Substitution(new Word('mouse'), new Word('mice'));
        yield new Substitution(new Word('goose'), new Word('geese'));
        yield new Substitution(new Word('ox'), new Word('oxen'));

        // частые для доменных моделей/ORM
        yield new Substitution(new Word('medium'), new Word('media'));
        yield new Substitution(new Word('criterion'), new Word('criteria'));
        yield new Substitution(new Word('analysis'), new Word('analyses'));
        yield new Substitution(new Word('thesis'), new Word('theses'));
        yield new Substitution(new Word('diagnosis'), new Word('diagnoses'));

        yield new Substitution(new Word('leaf'), new Word('leaves'));
        yield new Substitution(new Word('loaf'), new Word('loaves'));
        yield new Substitution(new Word('thief'), new Word('thieves'));
        yield new Substitution(new Word('knife'), new Word('knives'));
    }
}
