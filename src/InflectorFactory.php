<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector;

use PhpSoftBox\Inflector\Contracts\InflectorInterface;

/**
 * Корневая фабрика инфлекторов.
 *
 * Делегирует создание конкретного набора правил в language-specific фабрики.
 */
final class InflectorFactory
{
    public static function create(LanguageEnum $language): InflectorInterface
    {
        return match ($language) {
            LanguageEnum::EN => Rules\En\InflectorFactory::create(),
        };
    }
}
