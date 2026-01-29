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

Сейчас реализован только английский:

```php
$inflector = InflectorFactory::create(LanguageEnum::EN);
```

В следующих релизах можно будет добавить русский язык, просто реализовав правила в `src/Rules/Ru/...` и добавив `LanguageEnum::RU`.

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
