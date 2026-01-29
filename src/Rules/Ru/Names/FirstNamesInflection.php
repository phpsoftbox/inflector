<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru\Names;

use PhpSoftBox\Inflector\Names\Gender;

use function array_diff;
use function array_fill_keys;
use function in_array;
use function str_replace;

final class FirstNamesInflection extends NamesInflection
{
    /**
     * @var array<string, array<string, string>>
     */
    private static array $exceptions = [
        'лев' => [
            self::IMENIT  => 'Лев',
            self::RODIT   => 'Льва',
            self::DAT     => 'Льву',
            self::VINIT   => 'Льва',
            self::TVORIT  => 'Львом',
            self::PREDLOJ => 'Льве',
        ],
        'павел' => [
            self::IMENIT  => 'Павел',
            self::RODIT   => 'Павла',
            self::DAT     => 'Павлу',
            self::VINIT   => 'Павла',
            self::TVORIT  => 'Павлом',
            self::PREDLOJ => 'Павле',
        ],
    ];

    /** @var list<string> */
    private static array $menNames = [
        'александр', 'алексей', 'андрей', 'антон', 'артем', 'артём', 'богдан', 'борис', 'вадим',
        'василий', 'виктор', 'виталий', 'владимир', 'владислав', 'вячеслав', 'георгий', 'глеб',
        'григорий', 'даниил', 'денис', 'дмитрий', 'евгений', 'егор', 'иван', 'игорь', 'илья',
        'кирилл', 'константин', 'лев', 'леонид', 'максим', 'матвей', 'михаил', 'никита', 'николай',
        'олег', 'павел', 'петр', 'пётр', 'роман', 'сергей', 'тимофей', 'фёдор', 'федор', 'юрий', 'ярослав',
    ];

    /** @var list<string> */
    private static array $womenNames = [
        'александра', 'алина', 'алиса', 'анастасия', 'анна', 'валентина', 'валерия', 'варвара',
        'вера', 'вероника', 'виктория', 'галина', 'дарья', 'диана', 'евгения', 'екатерина', 'елена',
        'елизавета', 'жанна', 'злата', 'зоя', 'инна', 'ирина', 'карина', 'кристина', 'ксения',
        'лариса', 'лидия', 'любовь', 'людмила', 'маргарита', 'марина', 'мария', 'наталия', 'наталья',
        'нина', 'оксана', 'ольга', 'полина', 'светлана', 'софия', 'софья', 'таисия', 'тамара', 'татьяна',
        'ульяна', 'юлия', 'яна',
    ];

    /** @var list<string> */
    private static array $immutableNames = ['николя'];

    public static function getCase(string $name, string $case, ?string $gender = null): string
    {
        $forms = self::getCases($name, $gender);
        $case  = CasesHelper::canonize($case);

        return $forms[$case] ?? StringHelper::title($name);
    }

    /**
     * @return array<string, string>
     */
    public static function getCases(string $name, ?string $gender = null): array
    {
        $name = StringHelper::lower($name);

        if (self::isMutable($name, $gender)) {
            if (StringHelper::slice($name, -2) === 'ия') {
                $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

                return [
                    self::IMENIT  => $prefix . 'я',
                    self::RODIT   => $prefix . 'и',
                    self::DAT     => $prefix . 'и',
                    self::VINIT   => $prefix . 'ю',
                    self::TVORIT  => $prefix . 'ей',
                    self::PREDLOJ => $prefix . 'и',
                ];
            }

            if (StringHelper::slice($name, -1) === 'я') {
                $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

                return [
                    self::IMENIT  => $prefix . 'я',
                    self::RODIT   => $prefix . 'и',
                    self::DAT     => $prefix . 'е',
                    self::VINIT   => $prefix . 'ю',
                    self::TVORIT  => $prefix . 'ей',
                    self::PREDLOJ => $prefix . 'е',
                ];
            }

            if (!in_array($name, self::$immutableNames, true)) {
                $gender ??= self::detectGender($name);

                if ($gender === Gender::MALE->value || $name === 'саша') {
                    $cases = self::getCasesMan($name);
                    if ($cases !== null) {
                        return $cases;
                    }
                } elseif ($gender === Gender::FEMALE->value) {
                    $cases = self::getCasesWoman($name);
                    if ($cases !== null) {
                        return $cases;
                    }
                }
            }
        }

        $normalized = StringHelper::title($name);

        return array_fill_keys(CasesHelper::allCases(), $normalized);
    }

    public static function isMutable(string $name, ?string $gender = null): bool
    {
        if (StringHelper::length($name) === 1) {
            return false;
        }

        $name = StringHelper::lower($name);

        if (in_array($name, self::$immutableNames, true)) {
            return false;
        }

        $gender ??= self::detectGender($name);

        if ($gender === Gender::MALE->value) {
            if (StringHelper::slice($name, -1) === 'ь' && Language::isConsonant(StringHelper::slice($name, -2, 1))) {
                return true;
            }
            if (in_array(StringHelper::slice($name, -1), array_diff(Language::$consonants, ['й']), true)) {
                return true;
            }
            if (StringHelper::slice($name, -1) === 'й') {
                return true;
            }
            if (in_array(StringHelper::slice($name, -2), ['ло', 'ко'], true)) {
                return true;
            }
        } elseif ($gender === Gender::FEMALE->value) {
            if (StringHelper::slice($name, -1) === 'ь' && Language::isConsonant(StringHelper::slice($name, -2, 1))) {
                return true;
            }
            if (Language::isHissingConsonant(StringHelper::slice($name, -1))) {
                return true;
            }
        }

        if (
            (in_array(StringHelper::slice($name, -1), ['а', 'я'], true) && !Language::isVowel(StringHelper::slice($name, -2, 1)))
            || in_array(StringHelper::slice($name, -2), ['ия', 'ья', 'ея', 'оя'], true)
        ) {
            return true;
        }

        return false;
    }

    public static function detectGender(string $name): ?string
    {
        $name = StringHelper::lower($name);
        if (in_array($name, self::$menNames, true)) {
            return Gender::MALE->value;
        }
        if (in_array($name, self::$womenNames, true)) {
            return Gender::FEMALE->value;
        }

        $man   = 0.0;
        $woman = 0.0;
        $last1 = StringHelper::slice($name, -1);
        $last2 = StringHelper::slice($name, -2);
        $last3 = StringHelper::slice($name, -3);

        if ($last1 === 'й') {
            $man += 0.9;
        }
        if ($last1 === 'ь') {
            $man += 0.02;
        }
        if (in_array($last1, Language::$consonants, true)) {
            $man += 0.01;
        }
        if (in_array($last1, Language::$vowels, true)) {
            $woman += 0.01;
        }
        if (in_array($last2, ['он', 'ов', 'ав', 'ам', 'ол', 'ан', 'рд', 'мп'], true)) {
            $man += 0.3;
        }
        if (in_array($last2, ['вь', 'фь', 'ль'], true)) {
            $woman += 0.1;
        }
        if (in_array($last2, ['ла'], true)) {
            $woman += 0.04;
        }
        if (in_array($last2, ['то', 'ма'], true)) {
            $man += 0.01;
        }
        if (in_array($last3, ['лья', 'вва', 'ока', 'ука', 'ита'], true)) {
            $man += 0.2;
        }
        if (in_array($last3, ['има'], true)) {
            $woman += 0.15;
        }
        if (in_array($last3, ['лия', 'ния', 'сия', 'дра', 'лла', 'кла', 'опа', 'ора'], true)) {
            $woman += 0.5;
        }
        if (in_array(StringHelper::slice($name, -4), ['льда', 'фира', 'нина', 'тина', 'лита', 'алья', 'аида'], true)) {
            $woman += 0.5;
        }

        if ($man === $woman) {
            return null;
        }

        return $man > $woman ? Gender::MALE->value : Gender::FEMALE->value;
    }

    /**
     * @return array<string, string>|null
     */
    private static function getCasesMan(string $name): ?array
    {
        if (isset(self::$exceptions[$name])) {
            return self::$exceptions[$name];
        }

        if (in_array(StringHelper::slice($name, -1), array_diff(Language::$consonants, ['й']), true)) {
            if (in_array(StringHelper::slice($name, -2), ['ек', 'ёк'], true)) {
                $before = StringHelper::slice($name, -4, 1);
                if (Language::isConsonant($before) || $before === 'ы') {
                    $prefix = StringHelper::title(StringHelper::slice($name, 0, -2)) . 'ек';
                } else {
                    $prefix = StringHelper::title(StringHelper::slice($name, 0, -2)) . 'ьк';
                }
            } else {
                $prefix = $name === 'пётр'
                    ? StringHelper::title(str_replace('ё', 'е', $name))
                    : StringHelper::title($name);
            }

            $ending = Language::isHissingConsonant(StringHelper::slice($name, -1)) || StringHelper::slice($name, -1) === 'ц'
                ? 'ем'
                : 'ом';

            return [
                self::IMENIT  => StringHelper::title($name),
                self::RODIT   => $prefix . 'а',
                self::DAT     => $prefix . 'у',
                self::VINIT   => $prefix . 'а',
                self::TVORIT  => $prefix . $ending,
                self::PREDLOJ => $prefix . 'е',
            ];
        }

        if (StringHelper::slice($name, -1) === 'ь' && Language::isConsonant(StringHelper::slice($name, -2, 1))) {
            $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

            return [
                self::IMENIT  => $prefix . 'ь',
                self::RODIT   => $prefix . 'я',
                self::DAT     => $prefix . 'ю',
                self::VINIT   => $prefix . 'я',
                self::TVORIT  => $prefix . 'ем',
                self::PREDLOJ => $prefix . 'е',
            ];
        }

        if (in_array(StringHelper::slice($name, -2), ['ай', 'ей', 'ой', 'уй', 'яй', 'юй', 'ий'], true)) {
            $prefix  = StringHelper::title(StringHelper::slice($name, 0, -1));
            $postfix = StringHelper::slice($name, -2) === 'ий' ? 'и' : 'е';

            return [
                self::IMENIT  => $prefix . 'й',
                self::RODIT   => $prefix . 'я',
                self::DAT     => $prefix . 'ю',
                self::VINIT   => $prefix . 'я',
                self::TVORIT  => $prefix . 'ем',
                self::PREDLOJ => $prefix . $postfix,
            ];
        }

        if (StringHelper::slice($name, -1) === 'а' && Language::isConsonant($before = StringHelper::slice($name, -2, 1)) && $before !== 'ц') {
            $prefix  = StringHelper::title(StringHelper::slice($name, 0, -1));
            $postfix = (Language::isHissingConsonant($before) || in_array($before, ['г', 'к', 'х'], true)) ? 'и' : 'ы';

            return [
                self::IMENIT  => $prefix . 'а',
                self::RODIT   => $prefix . $postfix,
                self::DAT     => $prefix . 'е',
                self::VINIT   => $prefix . 'у',
                self::TVORIT  => $prefix . ($before === 'ш' ? 'е' : 'о') . 'й',
                self::PREDLOJ => $prefix . 'е',
            ];
        }

        if (in_array(StringHelper::slice($name, -2), ['ло', 'ко'], true)) {
            $prefix  = StringHelper::title(StringHelper::slice($name, 0, -1));
            $postfix = StringHelper::slice($name, -2, 1) === 'к' ? 'и' : 'ы';

            return [
                self::IMENIT  => $prefix . 'о',
                self::RODIT   => $prefix . $postfix,
                self::DAT     => $prefix . 'е',
                self::VINIT   => $prefix . 'у',
                self::TVORIT  => $prefix . 'ой',
                self::PREDLOJ => $prefix . 'е',
            ];
        }

        return null;
    }

    /**
     * @return array<string, string>|null
     */
    private static function getCasesWoman(string $name): ?array
    {
        if (StringHelper::slice($name, -1) === 'а' && !Language::isVowel($before = StringHelper::slice($name, -2, 1))) {
            $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));
            if ($before !== 'ц') {
                $postfix = (Language::isHissingConsonant($before) || in_array($before, ['г', 'к', 'х'], true)) ? 'и' : 'ы';

                return [
                    self::IMENIT  => $prefix . 'а',
                    self::RODIT   => $prefix . $postfix,
                    self::DAT     => $prefix . 'е',
                    self::VINIT   => $prefix . 'у',
                    self::TVORIT  => $prefix . 'ой',
                    self::PREDLOJ => $prefix . 'е',
                ];
            }

            return [
                self::IMENIT  => $prefix . 'а',
                self::RODIT   => $prefix . 'ы',
                self::DAT     => $prefix . 'е',
                self::VINIT   => $prefix . 'у',
                self::TVORIT  => $prefix . 'ей',
                self::PREDLOJ => $prefix . 'е',
            ];
        }

        if (StringHelper::slice($name, -1) === 'ь' && Language::isConsonant(StringHelper::slice($name, -2, 1))) {
            $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

            return [
                self::IMENIT  => $prefix . 'ь',
                self::RODIT   => $prefix . 'и',
                self::DAT     => $prefix . 'и',
                self::VINIT   => $prefix . 'ь',
                self::TVORIT  => $prefix . 'ью',
                self::PREDLOJ => $prefix . 'и',
            ];
        }

        if (Language::isHissingConsonant(StringHelper::slice($name, -1))) {
            $prefix = StringHelper::title($name);

            return [
                self::IMENIT  => $prefix,
                self::RODIT   => $prefix . 'и',
                self::DAT     => $prefix . 'и',
                self::VINIT   => $prefix,
                self::TVORIT  => $prefix . 'ью',
                self::PREDLOJ => $prefix . 'и',
            ];
        }

        return null;
    }
}
