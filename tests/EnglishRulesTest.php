<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Tests;

use PhpSoftBox\Inflector\Inflector;
use PhpSoftBox\Inflector\InflectorFactory;
use PhpSoftBox\Inflector\LanguageEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(InflectorFactory::class)]
#[CoversClass(Inflector::class)]
final class EnglishRulesTest extends TestCase
{
    /**
     * Проверяет pluralize: базовые и расширенные правила английского.
     */
    #[Test]
    public function pluralize(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('posts', $inflector->pluralize('post'));
        self::assertSame('stories', $inflector->pluralize('story'));
        self::assertSame('boxes', $inflector->pluralize('box'));
        self::assertSame('buses', $inflector->pluralize('bus'));
        self::assertSame('knives', $inflector->pluralize('knife'));
        self::assertSame('analyses', $inflector->pluralize('analysis'));
    }

    /**
     * Проверяет singularize: базовые и расширенные правила английского.
     */
    #[Test]
    public function singularize(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('post', $inflector->singularize('posts'));
        self::assertSame('story', $inflector->singularize('stories'));
        self::assertSame('box', $inflector->singularize('boxes'));
        self::assertSame('bus', $inflector->singularize('buses'));
        self::assertSame('knife', $inflector->singularize('knives'));
        self::assertSame('analysis', $inflector->singularize('analyses'));
    }

    /**
     * Проверяет неправильные формы (irregular).
     */
    #[Test]
    public function irregularNouns(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('people', $inflector->pluralize('person'));
        self::assertSame('person', $inflector->singularize('people'));

        self::assertSame('men', $inflector->pluralize('man'));
        self::assertSame('man', $inflector->singularize('men'));

        self::assertSame('children', $inflector->pluralize('child'));
        self::assertSame('child', $inflector->singularize('children'));
    }

    /**
     * Проверяет неисчисляемые существительные (uncountable).
     */
    #[Test]
    public function uncountableNouns(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('information', $inflector->pluralize('information'));
        self::assertSame('information', $inflector->singularize('information'));

        self::assertSame('series', $inflector->pluralize('series'));
        self::assertSame('series', $inflector->singularize('series'));
    }

    /**
     * Проверяет сохранение регистра для irregular (Person -> People, PERSON -> PEOPLE).
     */
    #[Test]
    public function preservesCaseForIrregular(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('People', $inflector->pluralize('Person'));
        self::assertSame('PEOPLE', $inflector->pluralize('PERSON'));

        self::assertSame('Person', $inflector->singularize('People'));
        self::assertSame('PERSON', $inflector->singularize('PEOPLE'));
    }

    /**
     * Проверяет, что uninflected может быть задан через regex Pattern.
     */
    #[Test]
    public function regexUninflectedPatternsWork(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        // \w+ware$
        self::assertSame('hardware', $inflector->pluralize('hardware'));
        self::assertSame('hardware', $inflector->singularize('hardware'));
        self::assertSame('software', $inflector->pluralize('software'));
        self::assertSame('software', $inflector->singularize('software'));

        // \w+media
        self::assertSame('metadata', $inflector->pluralize('metadata'));
        self::assertSame('metadata', $inflector->singularize('metadata'));
    }

    /**
     * Проверяет наиболее частые доменные сущности.
     */
    #[Test]
    public function commonDomainWords(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('users', $inflector->pluralize('user'));
        self::assertSame('user', $inflector->singularize('users'));

        self::assertSame('clients', $inflector->pluralize('client'));
        self::assertSame('client', $inflector->singularize('clients'));

        self::assertSame('products', $inflector->pluralize('product'));
        self::assertSame('product', $inflector->singularize('products'));

        // news часто uncountable
        self::assertSame('news', $inflector->pluralize('news'));
        self::assertSame('news', $inflector->singularize('news'));

        self::assertSame('posts', $inflector->pluralize('post'));
        self::assertSame('post', $inflector->singularize('posts'));
    }

    /**
     * Проверяет edge-кейсы, которые регулярно встречаются при генерации имён (таблицы/колонки/сущности).
     */
    #[Test]
    public function namingEdgeCases(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('statuses', $inflector->pluralize('status'));
        self::assertSame('status', $inflector->singularize('statuses'));

        self::assertSame('menus', $inflector->pluralize('menu'));
        self::assertSame('menu', $inflector->singularize('menus'));

        self::assertSame('criteria', $inflector->pluralize('criterion'));
        self::assertSame('criterion', $inflector->singularize('criteria'));

        self::assertSame('buses', $inflector->pluralize('bus'));
        self::assertSame('bus', $inflector->singularize('buses'));
    }
}
