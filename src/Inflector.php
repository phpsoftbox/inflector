<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector;

use PhpSoftBox\Inflector\Contracts\InflectorInterface;
use RuntimeException;

use function function_exists;
use function iconv;
use function lcfirst;
use function mb_strtolower;
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
    public function __construct(
        private Ruleset $pluralRuleset,
        private Ruleset $singularRuleset,
    ) {
    }

    public function pluralize(string $word): string
    {
        return $this->inflect($word, $this->pluralRuleset);
    }

    public function singularize(string $word): string
    {
        return $this->inflect($word, $this->singularRuleset);
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
        if ($original === strtoupper($original)) {
            return strtoupper($replacementLower);
        }

        $first = $original[0] ?? '';
        if ($first !== '' && $first === strtoupper($first)) {
            return strtoupper($replacementLower[0]) . substr($replacementLower, 1);
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
}
