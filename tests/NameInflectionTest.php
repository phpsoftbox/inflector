<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Tests;

use PhpSoftBox\Inflector\Inflector;
use PhpSoftBox\Inflector\InflectorFactory;
use PhpSoftBox\Inflector\LanguageEnum;
use PhpSoftBox\Inflector\Names\Cases;
use PhpSoftBox\Inflector\Names\Gender;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(InflectorFactory::class)]
#[CoversClass(Inflector::class)]
#[CoversMethod(Inflector::class, 'getNameCase')]
#[CoversMethod(Inflector::class, 'getNameCases')]
#[CoversMethod(Inflector::class, 'detectNameGender')]
final class NameInflectionTest extends TestCase
{
    /**
     * Проверяет, что EN-реализация склонения ФИО работает как заглушка.
     *
     * @see Inflector::detectNameGender()
     * @see Inflector::getNameCase()
     * @see Inflector::getNameCases()
     */
    #[Test]
    public function englishNameInflectionIsStub(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);
        $name      = 'John Doe';

        self::assertNull($inflector->detectNameGender($name));
        self::assertSame($name, $inflector->getNameCase($name, Cases::GENITIVE));

        $cases = $inflector->getNameCases($name);
        self::assertSame($name, $cases[Cases::NOMINATIVE->value]);
        self::assertSame($name, $cases[Cases::GENITIVE->value]);
        self::assertSame($name, $cases[Cases::DATIVE->value]);
        self::assertSame($name, $cases[Cases::ACCUSATIVE->value]);
        self::assertSame($name, $cases[Cases::ABLATIVE->value]);
        self::assertSame($name, $cases[Cases::PREPOSITIONAL->value]);
    }

    /**
     * Проверяет склонение мужского ФИО на русском языке.
     *
     * @see Inflector::detectNameGender()
     * @see Inflector::getNameCase()
     */
    #[Test]
    public function russianNameInflectionForMaleFullName(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);
        $name      = 'Иванов Иван Иванович';

        self::assertSame(Gender::MALE, $inflector->detectNameGender($name));
        self::assertSame('Иванова Ивана Ивановича', $inflector->getNameCase($name, Cases::fromAlias('родительный')));
        self::assertSame('Иванову Ивану Ивановичу', $inflector->getNameCase($name, Cases::DATIVE));
        self::assertSame('Иванова Ивана Ивановича', $inflector->getNameCase($name, Cases::ACCUSATIVE));
        self::assertSame('Ивановым Иваном Ивановичем', $inflector->getNameCase($name, Cases::ABLATIVE));
        self::assertSame('Иванове Иване Ивановиче', $inflector->getNameCase($name, Cases::PREPOSITIONAL));
    }

    /**
     * Проверяет склонение женского ФИО на русском языке.
     *
     * @see Inflector::detectNameGender()
     * @see Inflector::getNameCase()
     */
    #[Test]
    public function russianNameInflectionForFemaleFullName(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);
        $name      = 'Иванова Анна Ивановна';

        self::assertSame(Gender::FEMALE, $inflector->detectNameGender($name));
        self::assertSame('Ивановой Анны Ивановны', $inflector->getNameCase($name, Cases::fromAlias('р')));
        self::assertSame('Ивановой Анне Ивановне', $inflector->getNameCase($name, Cases::DATIVE));
        self::assertSame('Иванову Анну Ивановну', $inflector->getNameCase($name, Cases::ACCUSATIVE));
        self::assertSame('Ивановой Анной Ивановной', $inflector->getNameCase($name, Cases::ABLATIVE));
        self::assertSame('Ивановой Анне Ивановне', $inflector->getNameCase($name, Cases::PREPOSITIONAL));
    }

    /**
     * Проверим, что явно переданный Gender учитывается: мужская фамилия склоняется, при женском роде — нет.
     *
     * @see Inflector::getNameCase()
     */
    #[Test]
    public function russianNameInflectionUsesExplicitGender(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('Иванова Саши', $inflector->getNameCase('Иванов Саша', Cases::GENITIVE, Gender::MALE));
        self::assertSame('Иванов Саши', $inflector->getNameCase('Иванов Саша', Cases::GENITIVE, Gender::FEMALE));
    }
}
