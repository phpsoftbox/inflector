<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Tests;

use PhpSoftBox\Inflector\Inflector;
use PhpSoftBox\Inflector\InflectorFactory;
use PhpSoftBox\Inflector\LanguageEnum;
use PhpSoftBox\Inflector\Names\Cases;
use PhpSoftBox\Inflector\Names\Gender;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(InflectorFactory::class)]
#[CoversClass(Inflector::class)]
final class NameInflectionTest extends TestCase
{
    /**
     * Проверяет, что EN-реализация склонения ФИО работает как заглушка.
     */
    #[Test]
    public function englishNameInflectionIsStub(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);
        $name      = 'John Doe';

        self::assertNull($inflector->detectNameGender($name));
        self::assertSame($name, $inflector->getNameCase($name, Cases::GENITIVE->value));

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
     */
    #[Test]
    public function russianNameInflectionForMaleFullName(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);
        $name      = 'Иванов Иван Иванович';

        self::assertSame(Gender::MALE->value, $inflector->detectNameGender($name));
        self::assertSame('Иванова Ивана Ивановича', $inflector->getNameCase($name, 'родительный'));
        self::assertSame('Иванову Ивану Ивановичу', $inflector->getNameCase($name, Cases::DATIVE->value));
        self::assertSame('Иванова Ивана Ивановича', $inflector->getNameCase($name, Cases::ACCUSATIVE->value));
        self::assertSame('Ивановым Иваном Ивановичем', $inflector->getNameCase($name, Cases::ABLATIVE->value));
        self::assertSame('Иванове Иване Ивановиче', $inflector->getNameCase($name, Cases::PREPOSITIONAL->value));
    }

    /**
     * Проверяет склонение женского ФИО на русском языке.
     */
    #[Test]
    public function russianNameInflectionForFemaleFullName(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);
        $name      = 'Иванова Анна Ивановна';

        self::assertSame(Gender::FEMALE->value, $inflector->detectNameGender($name));
        self::assertSame('Ивановой Анны Ивановны', $inflector->getNameCase($name, 'р'));
        self::assertSame('Ивановой Анне Ивановне', $inflector->getNameCase($name, Cases::DATIVE->value));
        self::assertSame('Иванову Анну Ивановну', $inflector->getNameCase($name, Cases::ACCUSATIVE->value));
        self::assertSame('Ивановой Анной Ивановной', $inflector->getNameCase($name, Cases::ABLATIVE->value));
        self::assertSame('Ивановой Анне Ивановне', $inflector->getNameCase($name, Cases::PREPOSITIONAL->value));
    }
}
