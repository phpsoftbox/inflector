<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Tests;

use PhpSoftBox\Inflector\Inflector;
use PhpSoftBox\Inflector\InflectorFactory;
use PhpSoftBox\Inflector\LanguageEnum;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Inflector::class)]
final class InflectorHelpersTest extends TestCase
{
    /**
     * Проверяет tableize(): перевод CamelCase в snake_case (для таблиц/колонок).
     */
    #[Test]
    public function tableize(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('user', $inflector->tableize('User'));
        self::assertSame('blog_post', $inflector->tableize('BlogPost'));
        self::assertSame('api_client', $inflector->tableize('ApiClient'));
    }

    /**
     * Проверяет classify(): перевод snake_case в PascalCase (для имён классов).
     */
    #[Test]
    public function classify(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('BlogPost', $inflector->classify('blog_post'));
        self::assertSame('BlogPost', $inflector->classify('blog-post'));
        self::assertSame('BlogPost', $inflector->classify('blog post'));
    }

    /**
     * Проверяет camelize(): перевод snake_case в camelCase (для полей/свойств).
     */
    #[Test]
    public function camelize(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('blogPost', $inflector->camelize('blog_post'));
    }

    /**
     * Проверяет capitalize(): настройка разделителей.
     */
    #[Test]
    public function capitalize(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('Top-O-The-Morning To All_of_you!', $inflector->capitalize('top-o-the-morning to all_of_you!'));
        self::assertSame('Top-O-The-Morning To All_Of_You!', $inflector->capitalize('top-o-the-morning to all_of_you!', '-_ '));
    }

    /**
     * Проверяет urlize(): формирует url-friendly slug.
     */
    #[Test]
    public function urlize(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::EN);

        self::assertSame('my-first-blog-post', $inflector->urlize('My first blog post'));
        self::assertSame('hello-world', $inflector->urlize('Hello, world!'));
    }
}
