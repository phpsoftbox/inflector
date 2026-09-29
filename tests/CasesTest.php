<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Tests;

use InvalidArgumentException;
use PhpSoftBox\Inflector\Names\Cases;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Cases::class)]
#[CoversMethod(Cases::class, 'fromAlias')]
final class CasesTest extends TestCase
{
    /**
     * Проверим, что fromAlias() принимает значение enum, русское название и сокращение без учёта регистра.
     *
     * @see Cases::fromAlias()
     */
    #[Test]
    public function fromAliasAcceptsKnownNames(): void
    {
        self::assertSame(Cases::GENITIVE, Cases::fromAlias('genitive'));
        self::assertSame(Cases::GENITIVE, Cases::fromAlias('Родительный'));
        self::assertSame(Cases::ABLATIVE, Cases::fromAlias('т'));
    }

    /**
     * Проверим, что неизвестный падеж — InvalidArgumentException (одинаково для всех языков).
     *
     * @see Cases::fromAlias()
     */
    #[Test]
    public function fromAliasRejectsUnknownName(): void
    {
        $this->expectException(InvalidArgumentException::class);

        Cases::fromAlias('vocative');
    }
}
