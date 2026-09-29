<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Tests;

use PhpSoftBox\Inflector\Inflector;
use PhpSoftBox\Inflector\InflectorFactory;
use PhpSoftBox\Inflector\LanguageEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Inflector::class)]
#[CoversMethod(Inflector::class, 'pluralize')]
#[CoversMethod(Inflector::class, 'singularize')]
final class EnglishEdgeCasesTest extends TestCase
{
    /**
     * Слова, которые уже стоят в единственном числе и оканчиваются на -s.
     *
     * @return array<string, array{string}>
     */
    public static function singularWordsEndingWithS(): array
    {
        return [
            'bus'      => ['bus'],
            'analysis' => ['analysis'],
            'status'   => ['status'],
            'class'    => ['class'],
            'address'  => ['address'],
            'virus'    => ['virus'],
        ];
    }

    /**
     * Проверим, что singularize() не отрезает -s у слова в единственном числе (bus, analysis).
     *
     * @see Inflector::singularize()
     */
    #[Test]
    #[DataProvider('singularWordsEndingWithS')]
    public function singularizeKeepsSingularWordEndingWithS(string $word): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame($word, $inflector->singularize($word));
    }

    /**
     * Проверим, что pluralize() не добавляет -s к irregular-форме множественного числа (criteria).
     *
     * @see Inflector::pluralize()
     */
    #[Test]
    public function pluralizeKeepsIrregularPluralForm(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('criteria', $inflector->pluralize('criteria'));
    }

    /**
     * Проверим, что множественное число с -sses/-uses снимается корректно (addresses → address).
     *
     * @see Inflector::singularize()
     */
    #[Test]
    public function singularizeHandlesDoubleSEnding(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('address', $inflector->singularize('addresses'));
    }

    /**
     * Проверим, что virus во множественном числе — viruses.
     *
     * @see Inflector::pluralize()
     */
    #[Test]
    public function pluralizesVirus(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('viruses', $inflector->pluralize('virus'));
    }
}
