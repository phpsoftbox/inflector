<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector;

use function function_exists;
use function iconv;
use function preg_replace;
use function preg_replace_callback;
use function str_replace;
use function strtr;

/**
 * Транслитерация в ASCII: кириллица (русский, украинский, белорусский, казахский алфавиты) по упрощённой схеме
 * «как в загранпаспорте/URL» (щ → shch, ж → zh, х → kh, ц → ts, ю → yu, я → ya, ъ и ь опускаются), затем
 * латиница с диакритикой (é → e) через iconv. Символы, которые не удалось привести к ASCII, удаляются.
 *
 * Используется в {@see Inflector::urlize()} и в SlugFilter пакета phpsoftbox/filter, чтобы slug и urlize давали
 * одинаковый результат.
 */
final class Transliterator
{
    private const array CYRILLIC = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'kh', 'ц' => 'ts',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya', 'і' => 'i', 'ї' => 'yi', 'є' => 'ye', 'ґ' => 'g', 'ў' => 'u', 'ә' => 'a', 'ғ' => 'g',
        'қ' => 'q', 'ң' => 'n', 'ө' => 'o', 'ұ' => 'u', 'ү' => 'u', 'һ' => 'h',
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'Y', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'Kh', 'Ц' => 'Ts',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Shch', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya', 'І' => 'I', 'Ї' => 'Yi', 'Є' => 'Ye', 'Ґ' => 'G', 'Ў' => 'U', 'Ә' => 'A', 'Ғ' => 'G',
        'Қ' => 'Q', 'Ң' => 'N', 'Ө' => 'O', 'Ұ' => 'U', 'Ү' => 'U', 'Һ' => 'H',
    ];

    /**
     * Приводит строку к ASCII: «Привет, мир» → «Privet, mir», «Café» → «Cafe».
     */
    public static function toAscii(string $value): string
    {
        $value = strtr($value, self::CYRILLIC);

        // Остаток не-ASCII (латиница с диакритикой и т.п.) транслитерируется по фрагментам: TRANSLIT зависит
        // от локали и libc и заменяет неизвестные символы на «?», поэтому «?» из результата фрагмента удаляется,
        // а «?» исходной строки сохраняется.
        return preg_replace_callback(
            '/[^\x00-\x7F]+/u',
            static function (array $match): string {
                $converted = function_exists('iconv') ? iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $match[0]) : false;

                return $converted === false ? '' : str_replace('?', '', preg_replace('/[^\x00-\x7F]/', '', $converted) ?? '');
            },
            $value,
        ) ?? '';
    }
}
