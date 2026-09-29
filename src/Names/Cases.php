<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Names;

use InvalidArgumentException;

use function array_map;
use function mb_strtolower;
use function sprintf;
use function trim;

/**
 * Падежи для склонения ФИО.
 */
enum Cases: string
{
    case NOMINATIVE    = 'nominative';
    case GENITIVE      = 'genitive';
    case DATIVE        = 'dative';
    case ACCUSATIVE    = 'accusative';
    case ABLATIVE      = 'ablative';
    case PREPOSITIONAL = 'prepositional';

    /**
     * @return list<value-of<Cases>>
     */
    public static function values(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }

    /**
     * Разбирает название падежа из строки (конфиг, ввод пользователя): значение enum ('genitive'),
     * русское название или сокращение ('родительный', 'родит', 'р'), однобуквенный код ('g').
     * Регистр не важен. Для всех языков неизвестное название — InvalidArgumentException.
     */
    public static function fromAlias(string $alias): self
    {
        return match (mb_strtolower(trim($alias))) {
            'nominative', 'именительный', 'именит', 'и', 'n'               => self::NOMINATIVE,
            'genitive', 'genetive', 'родительный', 'родит', 'р', 'g'       => self::GENITIVE,
            'dative', 'дательный', 'дат', 'д', 'd'                         => self::DATIVE,
            'accusative', 'винительный', 'винит', 'в'                      => self::ACCUSATIVE,
            'ablative', 'instrumental', 'творительный', 'творит', 'т', 'a' => self::ABLATIVE,
            'prepositional', 'предложный', 'предлож', 'п'                  => self::PREPOSITIONAL,
            default                                                        => throw new InvalidArgumentException(
                sprintf('Invalid case "%s".', $alias),
            ),
        };
    }
}
