# PhpSoftBox Inflector

`phpsoftbox/inflector` — компонент для преобразования слов между единственным и множественным числом.

Компонент **вдохновлён Doctrine Inflector**, но реализован в более лёгком и прагматичном виде, чтобы:
- не тянуть лишние зависимости,
- иметь предсказуемую архитектуру под мульти-языковые ruleset'ы,
- оставаться удобным для использования в ORM/DBAL.

## Установка

```bash
composer require phpsoftbox/inflector
```

## Быстрый старт

```php
use PhpSoftBox\Inflector\InflectorFactory;
use PhpSoftBox\Inflector\LanguageEnum;

$inflector = InflectorFactory::create(LanguageEnum::EN);

echo $inflector->pluralize('post');    // posts
echo $inflector->singularize('posts'); // post
```

`InflectorFactory::create()` возвращает `Contracts\InflectorInterface`; все методы ниже входят в интерфейс.

## Дополнительные методы (конвенции/ORM)

Инфлектор также содержит небольшой набор утилит, часто полезных в ORM и соглашениях по именованию:

```php
$inflector->tableize('BlogPost');     // blog_post
$inflector->classify('blog_post');    // BlogPost
$inflector->camelize('blog_post');    // blogPost
$inflector->capitalize('top-o-the-morning to all_of_you!'); // Top-O-The-Morning To All_of_you!
$inflector->urlize('My first blog post'); // my-first-blog-post
$inflector->urlize('Привет мир 2024');    // privet-mir-2024
```

`urlize()` транслитерирует строку через `Transliterator::toAscii()` (кириллица: русский, украинский, белорусский,
казахский алфавиты; латиница с диакритикой — через iconv), приводит к нижнему регистру и заменяет любые
последовательности не-буквенно-цифровых символов одним дефисом. Символы без ASCII-аналога удаляются.
Схема транслитерации: `ё → yo`, `ж → zh`, `х → kh`, `ц → ts`, `ч → ch`, `ш → sh`, `щ → shch`, `ы → y`, `й → y`,
`ю → yu`, `я → ya`, `ъ`/`ь` опускаются. `SlugFilter` пакета `phpsoftbox/filter` использует ту же транслитерацию.

```php
use PhpSoftBox\Inflector\Transliterator;

Transliterator::toAscii('Склад №1, Café'); // 'Sklad No1, Cafe'
```

## Уже склонённые слова

Если слово уже стоит в целевой форме и известно как irregular, оно возвращается без изменений:
`pluralize('people') → people`, `pluralize('criteria') → criteria`, `pluralize('люди') → люди`. Слова на `-s` в
единственном числе (`bus`, `status`, `analysis`, `class`) `singularize()` не меняет.

## Поддержка языков

Язык выбирается через `LanguageEnum`.

Сейчас реализованы:
- `EN`
- `RU` — именительный падеж для частых доменных слов (см. ниже)

```php
$inflector = InflectorFactory::create(LanguageEnum::EN);
$ruInflector = InflectorFactory::create(LanguageEnum::RU);
```

Пример RU:

```php
use PhpSoftBox\Inflector\InflectorFactory;
use PhpSoftBox\Inflector\LanguageEnum;

$inflector = InflectorFactory::create(LanguageEnum::RU);

echo $inflector->pluralize('миграция');   // миграции
echo $inflector->pluralize('банк');       // банки
echo $inflector->pluralize('ключ');       // ключи
echo $inflector->singularize('товары');   // товар
echo $inflector->singularize('банки');    // банк
echo $inflector->singularize('люди');     // человек
echo $inflector->pluralizeByCount(21, 'миграцию', 'миграции', 'миграций'); // миграцию
```

Русские правила (`src/Rules/Ru`):
- учитывают орфографию: после `г, к, х, ж, ч, ш, щ` пишется `и` (`банк → банки`, `задача → задачи`), `-ия → -ии`,
  `-ие → -ия`, `-ь/-й/-я → -и`, `-о → -а`, `-ец → -цы`, прилагательные `-ая → -ые`;
- `singularize()` по форме множественного числа не всегда может определить род (`банки` — «банк» или «банка»,
  `книги` — «книг» или «книга»). Правила выбирают вариант, частый для доменных слов (`заявки → заявка`,
  `сотрудники → сотрудник`, `ключи → ключ`), остальное — исключения (irregular): `человек/люди`, `ребенок/дети`,
  `день/дни`, `адрес/адреса`, `счет/счета`, `банк/банки`, `остаток/остатки`, `платеж/платежи` и др.
  Для пар с `ё`/`е` (`ребёнок`/`ребенок`) `singularize()` возвращает вариант с `е`;
- несклоняемые слова (`кофе`, `такси`, `меню`, аббревиатуры `ИНН`, `КИЗ`, слова латиницей) и слова только
  множественного числа (`деньги`, `сутки`, `данные`) не меняются;
- кириллическое слово целиком в верхнем регистре остаётся в верхнем регистре (`ТОВАР → ТОВАРЫ`), первая
  заглавная буква сохраняется (`Банк → Банки`).

Если нужного слова нет в правилах — добавьте его в `Inflectable::getIrregular()` и тест
`tests/RussianNounsTest.php`.

## Склонение ФИО

В языках, где поддерживается склонение имён (сейчас `RU`), доступны:
- `getNameCases(string $fullName, ?Gender $gender = null): array` — ключи массива: значения `Cases`
  (`'genitive'` и т.д.);
- `getNameCase(string $fullName, Cases $case, ?Gender $gender = null): string`;
- `detectNameGender(string $fullName): ?Gender`.

Падеж и пол — enum'ы `Names\Cases` и `Names\Gender` (`MALE = 'm'`, `FEMALE = 'f'`), поэтому неверный падеж
невозможно передать ни для одного языка. Строку (конфиг, ввод) разбирает `Cases::fromAlias()`: принимает значение
enum, русское название или сокращение (`'родительный'`, `'родит'`, `'р'`), однобуквенный код (`'g'`); неизвестное
название — `InvalidArgumentException` для любого языка.

Для `EN` используется заглушка: имя возвращается без изменений, пол `null`.

```php
use PhpSoftBox\Inflector\Names\Cases;
use PhpSoftBox\Inflector\Names\Gender;

$ru = InflectorFactory::create(LanguageEnum::RU);
$en = InflectorFactory::create(LanguageEnum::EN);

echo $ru->getNameCase('Иванов Иван Иванович', Cases::GENITIVE);
// Иванова Ивана Ивановича

echo $ru->getNameCase('Иванов Иван Иванович', Cases::fromAlias('д'));
// Иванову Ивану Ивановичу

$allCases = $ru->getNameCases('Иванова Анна Ивановна');
$gender = $ru->detectNameGender('Иванова Анна Ивановна'); // Gender::FEMALE

echo $en->getNameCase('John Doe', Cases::GENITIVE); // John Doe
```

## Как устроены правила (rules)

Структура rules-слоя похожа на Doctrine Inflector:

- `Pattern` — паттерн (может быть регуляркой `/.../` или строкой/regex-фрагментом).
- `Transformation` — правило замены по совпадению `Pattern`.
- `Substitution` + `Word` — "неправильные" формы (irregular).
- `Ruleset` — объединяет:
  - `Transformations` (regular)
  - `Patterns` (uninflected)
  - `Substitutions` (irregular)

Пример: английские правила располагаются в `src/Rules/En`:

- `Inflectable` — склоняемые правила (plural/singular + irregular)
- `Uninflected` — слова/паттерны, которые **не склоняются**
- `Rules` — сборка двух ruleset'ов (plural/singular)

## Примеры

### Irregular

```php
$inflector = InflectorFactory::create(LanguageEnum::EN);

echo $inflector->pluralize('person'); // people
echo $inflector->singularize('people'); // person
```

### Uninflected + regex

Uninflected можно задавать через паттерны, включая regex:

```php
$inflector = InflectorFactory::create(LanguageEnum::EN);

echo $inflector->pluralize('hardware'); // hardware
echo $inflector->pluralize('metadata'); // metadata
```

## Тесты

В репозитории PhpSoftBox тесты запускаются, как правило, через Makefile.

```bash
make select-inflector
make php-test
```
