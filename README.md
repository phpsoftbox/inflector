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

## Дополнительные методы (конвенции/ORM)

Инфлектор также содержит небольшой набор утилит, часто полезных в ORM и соглашениях по именованию:

```php
$inflector->tableize('BlogPost');     // blog_post
$inflector->classify('blog_post');    // BlogPost
$inflector->camelize('blog_post');    // blogPost
$inflector->capitalize('top-o-the-morning to all_of_you!'); // Top-O-The-Morning To All_of_you!
$inflector->urlize('My first blog post'); // my-first-blog-post
```

## Поддержка языков

Язык выбирается через `LanguageEnum`.

Сейчас реализованы:
- `EN`
- `RU` (базовый набор rules для частых доменных слов)

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
echo $inflector->singularize('товары');   // товар
echo $inflector->pluralizeByCount(21, 'миграцию', 'миграции', 'миграций'); // миграцию
```

## Склонение ФИО

В языках, где поддерживается склонение имён (сейчас `RU`), доступны:
- `getNameCases(string $fullName, ?string $gender = null): array`
- `getNameCase(string $fullName, string $case, ?string $gender = null): string`
- `detectNameGender(string $fullName): ?string`

Для `EN` используется заглушка: имя возвращается без изменений, пол `null`.

```php
use PhpSoftBox\Inflector\Names\Cases;

$ru = InflectorFactory::create(LanguageEnum::RU);
$en = InflectorFactory::create(LanguageEnum::EN);

echo $ru->getNameCase('Иванов Иван Иванович', Cases::GENITIVE->value);
// Иванова Ивана Ивановича

$allCases = $ru->getNameCases('Иванова Анна Ивановна');
$gender = $ru->detectNameGender('Иванова Анна Ивановна'); // f

echo $en->getNameCase('John Doe', Cases::GENITIVE->value); // John Doe
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
