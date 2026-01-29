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
final class RussianRulesTest extends TestCase
{
    /**
     * Проверяет pluralize: базовые правила русского склонения.
     */
    #[Test]
    public function pluralize(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('миграции', $inflector->pluralize('миграция'));
        self::assertSame('категории', $inflector->pluralize('категория'));
        self::assertSame('компании', $inflector->pluralize('компания'));
        self::assertSame('товары', $inflector->pluralize('товар'));
        self::assertSame('модели', $inflector->pluralize('модель'));
        self::assertSame('КАТЕГОРИИ', $inflector->pluralize('КАТЕГОРИЯ'));
    }

    /**
     * Проверяет singularize: обратные правила для русского склонения.
     */
    #[Test]
    public function singularize(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('миграция', $inflector->singularize('миграции'));
        self::assertSame('категория', $inflector->singularize('категории'));
        self::assertSame('компания', $inflector->singularize('компании'));
        self::assertSame('товар', $inflector->singularize('товары'));
        self::assertSame('модель', $inflector->singularize('модели'));
        self::assertSame('КАТЕГОРИЯ', $inflector->singularize('КАТЕГОРИИ'));
    }

    /**
     * Проверяет слова, которые не должны изменяться при pluralize/singularize.
     */
    #[Test]
    public function uninflectedWords(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('киз', $inflector->pluralize('киз'));
        self::assertSame('киз', $inflector->singularize('киз'));
        self::assertSame('API', $inflector->pluralize('API'));
        self::assertSame('sms', $inflector->singularize('sms'));
    }

    /**
     * Проверяет выбор формы слова по числу для RU-правил (1/2-4/5+).
     */
    #[Test]
    public function pluralizeByCount(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('миграцию', $inflector->pluralizeByCount(1, 'миграцию', 'миграции', 'миграций'));
        self::assertSame('миграции', $inflector->pluralizeByCount(2, 'миграцию', 'миграции', 'миграций'));
        self::assertSame('миграции', $inflector->pluralizeByCount(4, 'миграцию', 'миграции', 'миграций'));
        self::assertSame('миграций', $inflector->pluralizeByCount(5, 'миграцию', 'миграции', 'миграций'));
        self::assertSame('миграций', $inflector->pluralizeByCount(11, 'миграцию', 'миграции', 'миграций'));
        self::assertSame('миграцию', $inflector->pluralizeByCount(21, 'миграцию', 'миграции', 'миграций'));
        self::assertSame('миграции', $inflector->pluralizeByCount(24, 'миграцию', 'миграции', 'миграций'));
        self::assertSame('миграций', $inflector->pluralizeByCount(25, 'миграцию', 'миграции', 'миграций'));
    }
}
