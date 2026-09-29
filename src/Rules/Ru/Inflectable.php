<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru;

use PhpSoftBox\Inflector\Rules\Pattern;
use PhpSoftBox\Inflector\Rules\Substitution;
use PhpSoftBox\Inflector\Rules\Transformation;
use PhpSoftBox\Inflector\Rules\Word;

/**
 * Inflectable для русского языка (именительный падеж).
 *
 * Правила учитывают орфографию: после г, к, х, ж, ч, ш, щ пишется «и», а не «ы» (банк → банки, ключ → ключи).
 * Обратное преобразование неоднозначно (банки → банк или банка, книги → книг или книга), поэтому правила
 * singular выбирают самый частый для доменных слов вариант, а исключения перечислены в {@see getIrregular()}.
 * Беглые гласные (остаток → остатки) и чередования (человек → люди) — только через irregular.
 */
final class Inflectable
{
    /**
     * @return iterable<Transformation>
     */
    public static function getPlural(): iterable
    {
        // позиция -> позиции
        yield new Transformation(new Pattern('/ия$/iu'), 'ии');

        // сообщение -> сообщения, условие -> условия
        yield new Transformation(new Pattern('/ие$/iu'), 'ия');

        // платье -> платья
        yield new Transformation(new Pattern('/ье$/iu'), 'ья');

        // сердце -> сердца
        yield new Transformation(new Pattern('/це$/iu'), 'ца');

        // поле -> поля, море -> моря
        yield new Transformation(new Pattern('/([лр])е$/iu'), '$1я');

        // Субстантивированные прилагательные: накладная -> накладные, рабочий -> рабочие
        yield new Transformation(new Pattern('/([гкхжчшщ])(ая|ий)$/iu'), '$1ие');
        yield new Transformation(new Pattern('/(ая|ый)$/iu'), 'ые');

        // книга -> книги, задача -> задачи, заявка -> заявки
        yield new Transformation(new Pattern('/([гкхжчшщ])а$/iu'), '$1и');

        // группа -> группы, работа -> работы
        yield new Transformation(new Pattern('/а$/iu'), 'ы');

        // неделя -> недели, статья -> статьи
        yield new Transformation(new Pattern('/я$/iu'), 'и');

        // продавец -> продавцы, образец -> образцы (беглая «е»)
        yield new Transformation(new Pattern('/([бвгджзклмнпрстфхцчшщ])ец$/iu'), '$1цы');

        // заголовок -> заголовки, остаток -> остатки, список -> списки (беглая «о»)
        yield new Transformation(new Pattern('/(ат|ят|ис|ов|ар|ез|уд|ус)ок$/iu'), '$1ки');

        // банк -> банки, ключ -> ключи, нож -> ножи, товарищ -> товарищи
        yield new Transformation(new Pattern('/([гкхжчшщ])$/iu'), '$1и');

        // музей -> музеи, случай -> случаи
        yield new Transformation(new Pattern('/й$/iu'), 'и');

        // модель -> модели, пользователь -> пользователи
        yield new Transformation(new Pattern('/ь$/iu'), 'и');

        // место -> места, письмо -> письма
        yield new Transformation(new Pattern('/о$/iu'), 'а');

        // товар -> товары, склад -> склады, месяц -> месяцы
        yield new Transformation(new Pattern('/([бвдзлмнпрстфц])$/iu'), '$1ы');
    }

    /**
     * @return iterable<Transformation>
     */
    public static function getSingular(): iterable
    {
        // позиции -> позиция
        yield new Transformation(new Pattern('/ии$/iu'), 'ия');

        // сообщения -> сообщение, состояния -> состояние, задания -> задание, события -> событие, условия -> условие
        yield new Transformation(new Pattern('/([ея])ния$/iu'), '$1ние');
        yield new Transformation(new Pattern('/([^мп])ания$/iu'), '$1ание');
        yield new Transformation(new Pattern('/ытия$/iu'), 'ытие');
        yield new Transformation(new Pattern('/овия$/iu'), 'овие');

        // накладные -> накладная
        yield new Transformation(new Pattern('/ые$/iu'), 'ая');

        // статьи -> статья
        yield new Transformation(new Pattern('/ьи$/iu'), 'ья');

        // платья -> платье
        yield new Transformation(new Pattern('/ья$/iu'), 'ье');

        // слои -> слой, случаи -> случай
        yield new Transformation(new Pattern('/([аоуы])и$/iu'), '$1й');

        // идеи -> идея
        yield new Transformation(new Pattern('/еи$/iu'), 'ея');

        // услуги -> услуга, книги -> книга
        yield new Transformation(new Pattern('/([иу])ги$/iu'), '$1га');

        // сотрудники -> сотрудник, чеки -> чек, каталоги -> каталог, блоки -> блок
        yield new Transformation(new Pattern('/([аеёиоуыэюя][гкх])и$/iu'), '$1');

        // заявки -> заявка, поставки -> поставка, ячейки -> ячейка
        yield new Transformation(new Pattern('/([бвгджзйклмнпрстфхцчшщ])ки$/iu'), '$1ка');

        // задачи -> задача, выдачи -> выдача, продажи -> продажа
        yield new Transformation(new Pattern('/ачи$/iu'), 'ача');
        yield new Transformation(new Pattern('/дажи$/iu'), 'дажа');

        // ключи -> ключ, платежи -> платеж, стеллажи -> стеллаж
        yield new Transformation(new Pattern('/([жчшщ])и$/iu'), '$1');

        // страницы -> страница, единицы -> единица
        yield new Transformation(new Pattern('/ицы$/iu'), 'ица');

        // продавцы -> продавец, образцы -> образец
        yield new Transformation(new Pattern('/([бвгджзклмнпрстфхчшщ])цы$/iu'), '$1ец');

        // системы -> система, программы -> программа, группы -> группа, карты -> карта
        yield new Transformation(new Pattern('/(ем|мм|пп|рт)ы$/iu'), '$1а');

        // товары -> товар, склады -> склад, месяцы -> месяц
        yield new Transformation(new Pattern('/([бвдзлмнпрстфц])ы$/iu'), '$1');

        // модели -> модель, пользователи -> пользователь, связи -> связь
        yield new Transformation(new Pattern('/([бвдзлмнпрстф])и$/iu'), '$1ь');

        // места -> место, письма -> письмо
        yield new Transformation(new Pattern('/([бвгджзклмнпрстфхцчшщ])а$/iu'), '$1о');
    }

    /**
     * Исключения singular => plural. Для обратного словаря при совпадении plural побеждает последняя пара.
     *
     * @return iterable<Substitution>
     */
    public static function getIrregular(): iterable
    {
        // Чередования и супплетивные формы
        yield new Substitution(new Word('человек'), new Word('люди'));
        yield new Substitution(new Word('ребёнок'), new Word('дети'));
        yield new Substitution(new Word('ребенок'), new Word('дети'));
        yield new Substitution(new Word('день'), new Word('дни'));
        yield new Substitution(new Word('друг'), new Word('друзья'));
        yield new Substitution(new Word('брат'), new Word('братья'));
        yield new Substitution(new Word('стул'), new Word('стулья'));
        yield new Substitution(new Word('сын'), new Word('сыновья'));
        yield new Substitution(new Word('муж'), new Word('мужья'));
        yield new Substitution(new Word('имя'), new Word('имена'));
        yield new Substitution(new Word('время'), new Word('времена'));
        yield new Substitution(new Word('мать'), new Word('матери'));
        yield new Substitution(new Word('дочь'), new Word('дочери'));
        yield new Substitution(new Word('яблоко'), new Word('яблоки'));
        yield new Substitution(new Word('отец'), new Word('отцы'));
        yield new Substitution(new Word('лев'), new Word('львы'));

        // Мужской род на -а/-я во множественном числе
        yield new Substitution(new Word('адрес'), new Word('адреса'));
        yield new Substitution(new Word('город'), new Word('города'));
        yield new Substitution(new Word('дом'), new Word('дома'));
        yield new Substitution(new Word('директор'), new Word('директора'));
        yield new Substitution(new Word('доктор'), new Word('доктора'));
        yield new Substitution(new Word('мастер'), new Word('мастера'));
        yield new Substitution(new Word('номер'), new Word('номера'));
        yield new Substitution(new Word('паспорт'), new Word('паспорта'));
        yield new Substitution(new Word('поезд'), new Word('поезда'));
        yield new Substitution(new Word('счёт'), new Word('счета'));
        yield new Substitution(new Word('счет'), new Word('счета'));
        yield new Substitution(new Word('цвет'), new Word('цвета'));
        yield new Substitution(new Word('глаз'), new Word('глаза'));
        yield new Substitution(new Word('лес'), new Word('леса'));
        yield new Substitution(new Word('берег'), new Word('берега'));
        yield new Substitution(new Word('вечер'), new Word('вечера'));
        yield new Substitution(new Word('том'), new Word('тома'));
        yield new Substitution(new Word('край'), new Word('края'));
        yield new Substitution(new Word('учитель'), new Word('учителя'));

        // Singular неоднозначен: без исключения правила дали бы другой род или потеряли беглую гласную
        yield new Substitution(new Word('банк'), new Word('банки'));
        yield new Substitution(new Word('врач'), new Word('врачи'));
        yield new Substitution(new Word('этаж'), new Word('этажи'));
        yield new Substitution(new Word('платёж'), new Word('платежи'));
        yield new Substitution(new Word('платеж'), new Word('платежи'));
        yield new Substitution(new Word('остаток'), new Word('остатки'));
        yield new Substitution(new Word('заголовок'), new Word('заголовки'));
        yield new Substitution(new Word('список'), new Word('списки'));
        yield new Substitution(new Word('подарок'), new Word('подарки'));
        yield new Substitution(new Word('отрезок'), new Word('отрезки'));
        yield new Substitution(new Word('музей'), new Word('музеи'));
        yield new Substitution(new Word('неделя'), new Word('недели'));
        yield new Substitution(new Word('земля'), new Word('земли'));
        yield new Substitution(new Word('встреча'), new Word('встречи'));
        yield new Substitution(new Word('цена'), new Word('цены'));
        yield new Substitution(new Word('работа'), new Word('работы'));
        yield new Substitution(new Word('поле'), new Word('поля'));
        yield new Substitution(new Word('зона'), new Word('зоны'));
        yield new Substitution(new Word('смена'), new Word('смены'));
        yield new Substitution(new Word('палета'), new Word('палеты'));
        yield new Substitution(new Word('паллета'), new Word('паллеты'));
        yield new Substitution(new Word('море'), new Word('моря'));
    }
}
