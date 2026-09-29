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
final class RussianNounsTest extends TestCase
{
    /**
     * Типичные доменные слова: единственное и множественное число.
     *
     * @return array<string, array{string, string}>
     */
    public static function nouns(): array
    {
        return [
            'товар / товары'              => ['товар', 'товары'],
            'склад / склады'              => ['склад', 'склады'],
            'заказ / заказы'              => ['заказ', 'заказы'],
            'банк / банки'                => ['банк', 'банки'],
            'ключ / ключи'                => ['ключ', 'ключи'],
            'человек / люди'              => ['человек', 'люди'],
            'ребенок / дети'              => ['ребенок', 'дети'],
            'день / дни'                  => ['день', 'дни'],
            'пользователь / пользователи' => ['пользователь', 'пользователи'],
            'компания / компании'         => ['компания', 'компании'],
            'категория / категории'       => ['категория', 'категории'],
            'позиция / позиции'           => ['позиция', 'позиции'],
            'модель / модели'             => ['модель', 'модели'],
            'роль / роли'                 => ['роль', 'роли'],
            'задача / задачи'             => ['задача', 'задачи'],
            'книга / книги'               => ['книга', 'книги'],
            'заявка / заявки'             => ['заявка', 'заявки'],
            'поставка / поставки'         => ['поставка', 'поставки'],
            'ячейка / ячейки'             => ['ячейка', 'ячейки'],
            'продажа / продажи'           => ['продажа', 'продажи'],
            'группа / группы'             => ['группа', 'группы'],
            'документ / документы'        => ['документ', 'документы'],
            'сотрудник / сотрудники'      => ['сотрудник', 'сотрудники'],
            'поставщик / поставщики'      => ['поставщик', 'поставщики'],
            'клиент / клиенты'            => ['клиент', 'клиенты'],
            'адрес / адреса'              => ['адрес', 'адреса'],
            'платеж / платежи'            => ['платеж', 'платежи'],
            'остаток / остатки'           => ['остаток', 'остатки'],
            'заголовок / заголовки'       => ['заголовок', 'заголовки'],
            'место / места'               => ['место', 'места'],
            'правило / правила'           => ['правило', 'правила'],
            'сообщение / сообщения'       => ['сообщение', 'сообщения'],
            'уведомление / уведомления'   => ['уведомление', 'уведомления'],
            'задание / задания'           => ['задание', 'задания'],
            'условие / условия'           => ['условие', 'условия'],
            'неделя / недели'             => ['неделя', 'недели'],
            'месяц / месяцы'              => ['месяц', 'месяцы'],
            'статья / статьи'             => ['статья', 'статьи'],
            'музей / музеи'               => ['музей', 'музеи'],
            'случай / случаи'             => ['случай', 'случаи'],
            'слой / слои'                 => ['слой', 'слои'],
            'идея / идеи'                 => ['идея', 'идеи'],
            'услуга / услуги'             => ['услуга', 'услуги'],
            'каталог / каталоги'          => ['каталог', 'каталоги'],
            'чек / чеки'                  => ['чек', 'чеки'],
            'стеллаж / стеллажи'          => ['стеллаж', 'стеллажи'],
            'нож / ножи'                  => ['нож', 'ножи'],
            'товарищ / товарищи'          => ['товарищ', 'товарищи'],
            'страница / страницы'         => ['страница', 'страницы'],
            'единица / единицы'           => ['единица', 'единицы'],
            'продавец / продавцы'         => ['продавец', 'продавцы'],
            'образец / образцы'           => ['образец', 'образцы'],
            'система / системы'           => ['система', 'системы'],
            'программа / программы'       => ['программа', 'программы'],
            'связь / связи'               => ['связь', 'связи'],
            'поле / поля'                 => ['поле', 'поля'],
            'этаж / этажи'                => ['этаж', 'этажи'],
            'скидка / скидки'             => ['скидка', 'скидки'],
            'упаковка / упаковки'         => ['упаковка', 'упаковки'],
            'коробка / коробки'           => ['коробка', 'коробки'],
            'счет / счета'                => ['счет', 'счета'],
            'номер / номера'              => ['номер', 'номера'],
            'накладная / накладные'       => ['накладная', 'накладные'],
            'ошибка / ошибки'             => ['ошибка', 'ошибки'],
            'отгрузка / отгрузки'         => ['отгрузка', 'отгрузки'],
            'паллета / паллеты'           => ['паллета', 'паллеты'],
            'зона / зоны'                 => ['зона', 'зоны'],
            'штрихкод / штрихкоды'        => ['штрихкод', 'штрихкоды'],
            'этикетка / этикетки'         => ['этикетка', 'этикетки'],
            'операция / операции'         => ['операция', 'операции'],
            'сумма / суммы'               => ['сумма', 'суммы'],
        ];
    }

    /**
     * Проверим pluralize() на типичном слове с учётом орфографии (банк → банки, ключ → ключи).
     *
     * @see Inflector::pluralize()
     */
    #[Test]
    #[DataProvider('nouns')]
    public function pluralizesTypicalNoun(string $singular, string $plural): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame($plural, $inflector->pluralize($singular));
    }

    /**
     * Проверим singularize() на типичном слове (банки → банк, люди → человек).
     *
     * @see Inflector::singularize()
     */
    #[Test]
    #[DataProvider('nouns')]
    public function singularizesTypicalNoun(string $singular, string $plural): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame($singular, $inflector->singularize($plural));
    }

    /**
     * Проверим, что слово, уже стоящее во множественном числе как irregular, pluralize() не меняет.
     *
     * @see Inflector::pluralize()
     */
    #[Test]
    public function pluralizeKeepsIrregularPluralForm(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('люди', $inflector->pluralize('люди'));
    }

    /**
     * Проверим, что несклоняемое заимствование не меняется.
     *
     * @see Inflector::pluralize()
     */
    #[Test]
    public function pluralizeKeepsIndeclinableWord(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('кофе', $inflector->pluralize('кофе'));
    }

    /**
     * Проверим, что слово с заглавной буквы сохраняет регистр первой буквы.
     *
     * @see Inflector::pluralize()
     */
    #[Test]
    public function pluralizeKeepsCapitalLetter(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('Банки', $inflector->pluralize('Банк'));
    }

    /**
     * Проверим, что кириллическое слово в верхнем регистре остаётся в верхнем регистре.
     *
     * @see Inflector::pluralize()
     */
    #[Test]
    public function pluralizeKeepsUppercase(): void
    {
        $inflector = InflectorFactory::create(LanguageEnum::RU);

        self::assertSame('ТОВАРЫ', $inflector->pluralize('ТОВАР'));
    }
}
