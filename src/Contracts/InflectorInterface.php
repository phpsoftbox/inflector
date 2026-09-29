<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Contracts;

use PhpSoftBox\Inflector\Names\Cases;
use PhpSoftBox\Inflector\Names\Gender;

interface InflectorInterface
{
    /**
     * Множественное число по правилам языка. Слово, уже стоящее во множественном числе
     * и известное как irregular (people, люди), возвращается без изменений.
     */
    public function pluralize(string $word): string;

    /**
     * Единственное число по правилам языка.
     */
    public function singularize(string $word): string;

    /**
     * Выбор формы по числу (1 миграцию, 2 миграции, 5 миграций). Не зависит от языка инфлектора.
     */
    public function pluralizeByCount(int $count, string $one, string $few, string $many): string;

    /**
     * "ModelName" -> "model_name".
     */
    public function tableize(string $word): string;

    /**
     * "table_name" -> "TableName".
     */
    public function classify(string $word): string;

    /**
     * "table_name" -> "tableName".
     */
    public function camelize(string $word): string;

    /**
     * Первая буква каждого слова (слова разделяются $delimiters) — заглавная.
     */
    public function capitalize(string $string, string $delimiters = " \n\t\r\0\x0B-"): string;

    /**
     * URL-slug с транслитерацией: "Привет мир 2024" -> "privet-mir-2024".
     */
    public function urlize(string $string): string;

    /**
     * Все падежи ФИО: ключи — значения {@see Cases}.
     *
     * @return array<value-of<Cases>, string>
     */
    public function getNameCases(string $fullName, ?Gender $gender = null): array;

    public function getNameCase(string $fullName, Cases $case, ?Gender $gender = null): string;

    public function detectNameGender(string $fullName): ?Gender;
}
