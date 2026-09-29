<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Tests;

use PhpSoftBox\Inflector\Inflector;
use PhpSoftBox\Inflector\InflectorFactory;
use PhpSoftBox\Inflector\LanguageEnum;
use PhpSoftBox\Inflector\Transliterator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Inflector::class)]
#[CoversClass(Transliterator::class)]
#[CoversMethod(Inflector::class, 'urlize')]
#[CoversMethod(Transliterator::class, 'toAscii')]
final class UrlizeTest extends TestCase
{
    /**
     * Проверим, что urlize() транслитерирует кириллицу, а не выбрасывает её.
     *
     * @see Inflector::urlize()
     */
    #[Test]
    public function urlizeTransliteratesCyrillic(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('privet-mir-2024', $inflector->urlize('Привет мир 2024'));
    }

    /**
     * Проверим многобуквенные соответствия (щ, ж, ё) и латиницу с диакритикой.
     *
     * @see Inflector::urlize()
     */
    #[Test]
    public function urlizeHandlesMultiLetterSoundsAndDiacritics(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('shchuka-yozhik-cafe', $inflector->urlize('Щука, Ёжик & Café!'));
    }

    /**
     * Проверим, что toAscii() сохраняет регистр и знаки препинания, а мягкий знак опускает.
     *
     * @see Transliterator::toAscii()
     */
    #[Test]
    public function toAsciiKeepsCaseAndPunctuation(): void
    {
        self::assertSame('Sklad No1, yacheyka?', Transliterator::toAscii('Склад No1, ячейка?'));
        self::assertSame('Obyom', Transliterator::toAscii('Объём'));
    }

    /**
     * Проверим, что символы без ASCII-аналога удаляются, а не становятся «?».
     *
     * @see Transliterator::toAscii()
     */
    #[Test]
    public function toAsciiDropsUntransliterableCharacters(): void
    {
        self::assertSame('ab', Transliterator::toAscii('a日本b'));
    }
}
