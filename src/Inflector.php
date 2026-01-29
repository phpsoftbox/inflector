<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector;

use PhpSoftBox\Inflector\Contracts\InflectorInterface;
use PhpSoftBox\Inflector\Contracts\NameInflectionInterface;
use PhpSoftBox\Inflector\Names\BaseNameInflection;
use RuntimeException;

use function abs;
use function function_exists;
use function iconv;
use function lcfirst;
use function mb_strtolower;
use function mb_strtoupper;
use function mb_substr;
use function preg_replace;
use function sprintf;
use function str_replace;
use function strtolower;
use function strtoupper;
use function substr;
use function trim;
use function ucwords;

/**
 * Универсальный инфлектор.
 *
 * Реализация не привязана к языку. Язык задаётся через наборы Ruleset:
 * - pluralRuleset
 * - singularRuleset
 */
final readonly class Inflector implements InflectorInterface
{
    private NameInflectionInterface $nameInflection;

    public function __construct(
        private Ruleset $pluralRuleset,
        private Ruleset $singularRuleset,
        ?NameInflectionInterface $nameInflection = null,
    ) {
        $this->nameInflection = $nameInflection ?? new BaseNameInflection();
    }

    public function pluralize(string $word): string
    {
        return $this->inflect($word, $this->pluralRuleset);
    }

    public function singularize(string $word): string
    {
        return $this->inflect($word, $this->singularRuleset);
    }

    public function pluralizeByCount(int $count, string $one, string $few, string $many): string
    {
        $count  = abs($count);
        $mod100 = $count % 100;
        $mod10  = $mod100 % 10;

        if ($mod100 >= 11 && $mod100 <= 14) {
            return $many;
        }

        return match ($mod10) {
            1 => $one,
            2, 3, 4 => $few,
            default => $many,
        };
    }

    /**
     * @return array<string, string>
     */
    public function getNameCases(string $fullName, ?string $gender = null): array
    {
        return $this->nameInflection->getCases($fullName, $gender);
    }

    public function getNameCase(string $fullName, string $case, ?string $gender = null): string
    {
        return $this->nameInflection->getCase($fullName, $case, $gender);
    }

    public function detectNameGender(string $fullName): ?string
    {
        return $this->nameInflection->detectGender($fullName);
    }

    private function inflect(string $word, Ruleset $ruleset): string
    {
        $word = trim($word);
        if ($word === '') {
            return '';
        }

        if ($ruleset->getUninflected()->isUninflected($word)) {
            return $word;
        }

        $irregular = $ruleset->getIrregular()->get($word);
        if ($irregular !== null) {
            return $this->matchCase($word, $irregular);
        }

        return $ruleset->getRegular()->apply($word);
    }

    private function matchCase(string $original, string $replacementLower): string
    {
        if ($original === $this->toUpper($original)) {
            return $this->toUpper($replacementLower);
        }

        $firstOriginal = $this->substring($original, 0, 1);
        if ($firstOriginal !== '' && $firstOriginal === $this->toUpper($firstOriginal)) {
            $firstReplacement = $this->substring($replacementLower, 0, 1);
            $restReplacement  = $this->substring($replacementLower, 1);

            return $this->toUpper($firstReplacement) . $restReplacement;
        }

        return $replacementLower;
    }

    /**
     * Преобразует слово в формат названия таблицы.
     *
     * Пример: "ModelName" -> "model_name".
     */
    public function tableize(string $word): string
    {
        $tableized = preg_replace('~(?<=\w)([A-Z])~u', '_$1', $word);

        if ($tableized === null) {
            throw new RuntimeException(sprintf('preg_replace returned null for value "%s"', $word));
        }

        return function_exists('mb_strtolower')
            ? mb_strtolower($tableized)
            : strtolower($tableized);
    }

    /**
     * Преобразует слово в формат имени класса.
     *
     * Пример: "table_name" -> "TableName".
     */
    public function classify(string $word): string
    {
        return str_replace([' ', '_', '-'], '', ucwords($word, ' _-'));
    }

    /**
     * Camelize: как classify(), но с первой буквой в нижнем регистре.
     *
     * Пример: "table_name" -> "tableName".
     */
    public function camelize(string $word): string
    {
        return lcfirst($this->classify($word));
    }

    /**
     * Uppercases words with configurable delimiters between words.
     */
    public function capitalize(string $string, string $delimiters = " \n\t\r\0\x0B-"): string
    {
        return ucwords($string, $delimiters);
    }

    /**
     * Конвертирует строку в url-friendly формат.
     *
     * Пример: "My first blog post" -> "my-first-blog-post".
     */
    public function urlize(string $string): string
    {
        $unaccented = $this->unaccent($string);

        $lowered = function_exists('mb_strtolower')
            ? mb_strtolower($unaccented)
            : strtolower($unaccented);

        $replacements = [
            '/\W/'                   => ' ',
            '/([A-Z]+)([A-Z][a-z])/' => '\\1_\\2',
            '/([a-z\d])([A-Z])/'     => '\\1_\\2',
            '/[^A-Z^a-z^0-9^\/]+/'   => '-',
        ];

        $urlized = $lowered;

        foreach ($replacements as $pattern => $replacement) {
            $replaced = preg_replace($pattern, $replacement, $urlized);

            if ($replaced === null) {
                throw new RuntimeException(sprintf('preg_replace returned null for value "%s"', $urlized));
            }

            $urlized = $replaced;
        }

        return trim($urlized, '-');
    }

    /**
     * Упрощённое удаление диакритики.
     *
     * Для наших целей достаточно лёгкого варианта:
     * - если доступен iconv, пробуем translit
     * - иначе возвращаем как есть
     */
    private function unaccent(string $string): string
    {
        if (!function_exists('iconv')) {
            return $string;
        }

        $converted = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);

        return $converted === false ? $string : $converted;
    }

    private function toUpper(string $value): string
    {
        return function_exists('mb_strtoupper') ? mb_strtoupper($value) : strtoupper($value);
    }

    private function substring(string $value, int $start, ?int $length = null): string
    {
        if (function_exists('mb_substr')) {
            return $length === null ? mb_substr($value, $start) : mb_substr($value, $start, $length);
        }

        return $length === null ? substr($value, $start) : substr($value, $start, $length);
    }
}
