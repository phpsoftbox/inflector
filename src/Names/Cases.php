<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Names;

use function array_map;

enum Cases: string
{
    case NOMINATIVE    = 'nominative';
    case GENITIVE      = 'genitive';
    case DATIVE        = 'dative';
    case ACCUSATIVE    = 'accusative';
    case ABLATIVE      = 'ablative';
    case PREPOSITIONAL = 'prepositional';

    /**
     * @return list<string>
     */
    public static function values(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }
}
