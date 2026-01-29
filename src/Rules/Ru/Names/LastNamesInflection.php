<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru\Names;

use PhpSoftBox\Inflector\Names\Gender;

use function array_fill_keys;
use function explode;
use function in_array;
use function str_contains;

final class LastNamesInflection extends NamesInflection
{
    /** @var list<string> */
    private static array $womenPostfixes = ['ва', 'на', 'ая', 'яя'];

    /** @var list<string> */
    private static array $menPostfixes = ['ов', 'ев', 'ин', 'ын', 'ой', 'ий', 'ый', 'ич'];

    public static function getCase(string $name, string $case, ?string $gender = null): string
    {
        if (!self::isMutable($name, $gender)) {
            return StringHelper::title($name);
        }

        $forms = self::getCases($name, $gender);
        $case  = CasesHelper::canonize($case);

        return $forms[$case] ?? StringHelper::title($name);
    }

    public static function isMutable(string $name, ?string $gender = null): bool
    {
        if (StringHelper::length($name) === 1) {
            return false;
        }

        $name = StringHelper::lower($name);
        $gender ??= self::detectGender($name);

        if (str_contains($name, '-')) {
            foreach (explode('-', $name) as $part) {
                if (self::isMutable($part, $gender)) {
                    return true;
                }
            }

            return false;
        }

        if (in_array(StringHelper::slice($name, -1), ['а', 'я'], true)) {
            return true;
        }

        if (StringHelper::slice($name, -2) === 'их') {
            return false;
        }

        if ($gender === Gender::MALE->value) {
            if (in_array(StringHelper::slice($name, -2), ['ых', 'ко'], true)) {
                return false;
            }
            if (in_array(StringHelper::slice($name, -3), ['ово', 'аго'], true)) {
                return false;
            }
            if (in_array(StringHelper::slice($name, -2), ['ов', 'ев', 'ин', 'ын', 'ий', 'ой'], true)) {
                return true;
            }
            if (Language::isConsonant(StringHelper::slice($name, -1))) {
                return true;
            }
            if (StringHelper::slice($name, -1) === 'ь') {
                return true;
            }
        } else {
            if (in_array(StringHelper::slice($name, -2), ['ва', 'на', 'ая'], true)) {
                return true;
            }
        }

        return false;
    }

    public static function detectGender(string $name): ?string
    {
        $name = StringHelper::lower($name);

        if (in_array(StringHelper::slice($name, -2), self::$menPostfixes, true)) {
            return Gender::MALE->value;
        }
        if (in_array(StringHelper::slice($name, -2), self::$womenPostfixes, true)) {
            return Gender::FEMALE->value;
        }

        return null;
    }

    /**
     * @return array<string, string>
     */
    public static function getCases(string $name, ?string $gender = null): array
    {
        $name = StringHelper::lower($name);
        $gender ??= self::detectGender($name);

        if (str_contains($name, '-')) {
            $parts = explode('-', $name);
            foreach ($parts as $i => $part) {
                $parts[$i] = self::getCases($part, $gender);
            }

            return CasesHelper::composeCasesFromWords($parts, '-');
        }

        if (self::isMutable($name, $gender)) {
            if ($gender === Gender::MALE->value) {
                if (in_array(StringHelper::slice($name, -2), ['ов', 'ев', 'ин', 'ын', 'ёв'], true)) {
                    $prefix = StringHelper::title($name);

                    return [
                        self::IMENIT  => $prefix,
                        self::RODIT   => $prefix . 'а',
                        self::DAT     => $prefix . 'у',
                        self::VINIT   => $prefix . 'а',
                        self::TVORIT  => $prefix . 'ым',
                        self::PREDLOJ => $prefix . 'е',
                    ];
                }

                if (in_array(StringHelper::slice($name, -4), ['ский', 'ской', 'цкий', 'цкой'], true)) {
                    $prefix = StringHelper::title(StringHelper::slice($name, 0, -2));

                    return [
                        self::IMENIT  => StringHelper::title($name),
                        self::RODIT   => $prefix . 'ого',
                        self::DAT     => $prefix . 'ому',
                        self::VINIT   => $prefix . 'ого',
                        self::TVORIT  => $prefix . 'им',
                        self::PREDLOJ => $prefix . 'ом',
                    ];
                }

                if (in_array(StringHelper::slice($name, -2), ['ой', 'ый', 'ий'], true)) {
                    $lastConsonant = StringHelper::slice($name, -3, 1);
                    $lastSonority  = (Language::isSonorousConsonant($lastConsonant) && !in_array($lastConsonant, ['н', 'в'], true))
                        || $lastConsonant === 'ц';

                    if ($lastSonority) {
                        $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

                        return [
                            self::IMENIT  => StringHelper::title($name),
                            self::RODIT   => $prefix . 'я',
                            self::DAT     => $prefix . 'ю',
                            self::VINIT   => $prefix . 'я',
                            self::TVORIT  => $prefix . 'ем',
                            self::PREDLOJ => $prefix . (in_array(StringHelper::slice($name, -2), ['ой', 'ей'], true) ? 'е' : 'и'),
                        ];
                    }

                    $prefix = StringHelper::title(StringHelper::slice($name, 0, -2));

                    return [
                        self::IMENIT  => StringHelper::title($name),
                        self::RODIT   => $prefix . 'ого',
                        self::DAT     => $prefix . 'ому',
                        self::VINIT   => $prefix . 'ого',
                        self::TVORIT  => $prefix . 'ым',
                        self::PREDLOJ => $prefix . 'ом',
                    ];
                }

                if (in_array(StringHelper::slice($name, -2), ['ей', 'ай'], true)) {
                    $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

                    return [
                        self::IMENIT  => StringHelper::title($name),
                        self::RODIT   => $prefix . 'я',
                        self::DAT     => $prefix . 'ю',
                        self::VINIT   => $prefix . 'я',
                        self::TVORIT  => $prefix . 'ем',
                        self::PREDLOJ => $prefix . 'е',
                    ];
                }

                if (StringHelper::length($name) > 3 && StringHelper::slice($name, -2) === 'ок') {
                    $prefix = StringHelper::title(StringHelper::slice($name, 0, -2)) . StringHelper::slice($name, -1);

                    return [
                        self::IMENIT  => StringHelper::title($name),
                        self::RODIT   => $prefix . 'а',
                        self::DAT     => $prefix . 'у',
                        self::VINIT   => $prefix . 'а',
                        self::TVORIT  => $prefix . 'ом',
                        self::PREDLOJ => $prefix . 'е',
                    ];
                }

                if (StringHelper::length($name) > 3 && in_array(StringHelper::slice($name, -2), ['ек', 'ец'], true)) {
                    $lastConsonant = StringHelper::slice($name, -3, 1);
                    $prefix        = in_array($lastConsonant, ['л'], true)
                        ? StringHelper::title(StringHelper::slice($name, 0, -2)) . 'ь' . StringHelper::slice($name, -1)
                        : StringHelper::title(StringHelper::slice($name, 0, -2)) . StringHelper::slice($name, -1);

                    return [
                        self::IMENIT  => StringHelper::title($name),
                        self::RODIT   => $prefix . 'а',
                        self::DAT     => $prefix . 'у',
                        self::VINIT   => $prefix . 'а',
                        self::TVORIT  => $prefix . 'ом',
                        self::PREDLOJ => $prefix . 'е',
                    ];
                }
            } else {
                if (in_array(StringHelper::slice($name, -3), ['ова', 'ева', 'ина', 'ына', 'ёва'], true)) {
                    $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

                    return [
                        self::IMENIT  => StringHelper::title($name),
                        self::RODIT   => $prefix . 'ой',
                        self::DAT     => $prefix . 'ой',
                        self::VINIT   => $prefix . 'у',
                        self::TVORIT  => $prefix . 'ой',
                        self::PREDLOJ => $prefix . 'ой',
                    ];
                }

                if (StringHelper::slice($name, -2) === 'ая') {
                    $prefix = StringHelper::title(StringHelper::slice($name, 0, -2));

                    return [
                        self::IMENIT  => StringHelper::title($name),
                        self::RODIT   => $prefix . 'ой',
                        self::DAT     => $prefix . 'ой',
                        self::VINIT   => $prefix . 'ую',
                        self::TVORIT  => $prefix . 'ой',
                        self::PREDLOJ => $prefix . 'ой',
                    ];
                }

                if (StringHelper::slice($name, -2) === 'яя') {
                    $prefix = StringHelper::title(StringHelper::slice($name, 0, -2));

                    return [
                        self::IMENIT  => StringHelper::title($name),
                        self::RODIT   => $prefix . 'ей',
                        self::DAT     => $prefix . 'ей',
                        self::VINIT   => $prefix . 'юю',
                        self::TVORIT  => $prefix . 'ей',
                        self::PREDLOJ => $prefix . 'ей',
                    ];
                }
            }

            if (StringHelper::slice($name, -1) === 'я') {
                $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

                return [
                    self::IMENIT  => StringHelper::title($name),
                    self::RODIT   => $prefix . 'и',
                    self::DAT     => $prefix . 'е',
                    self::VINIT   => $prefix . 'ю',
                    self::TVORIT  => $prefix . 'ей',
                    self::PREDLOJ => $prefix . 'е',
                ];
            }

            if (StringHelper::slice($name, -1) === 'а') {
                $prefix   = StringHelper::title(StringHelper::slice($name, 0, -1));
                $last     = StringHelper::slice($name, -2, 1);
                $genitive = ((Language::isDeafConsonant($last) && $last !== 'п') || StringHelper::slice($name, -2) === 'га')
                    ? 'и'
                    : 'ы';

                return [
                    self::IMENIT  => StringHelper::title($name),
                    self::RODIT   => $prefix . $genitive,
                    self::DAT     => $prefix . 'е',
                    self::VINIT   => $prefix . 'у',
                    self::TVORIT  => $prefix . 'ой',
                    self::PREDLOJ => $prefix . 'е',
                ];
            }

            if (Language::isConsonant(StringHelper::slice($name, -1)) && StringHelper::slice($name, -2) !== 'ых') {
                $prefix = StringHelper::title($name);

                return [
                    self::IMENIT  => StringHelper::title($name),
                    self::RODIT   => $prefix . 'а',
                    self::DAT     => $prefix . 'у',
                    self::VINIT   => $prefix . 'а',
                    self::TVORIT  => $prefix . 'ом',
                    self::PREDLOJ => $prefix . 'е',
                ];
            }

            if (StringHelper::slice($name, -1) === 'ь' && $gender === Gender::MALE->value) {
                $prefix = StringHelper::title(StringHelper::slice($name, 0, -1));

                return [
                    self::IMENIT  => StringHelper::title($name),
                    self::RODIT   => $prefix . 'я',
                    self::DAT     => $prefix . 'ю',
                    self::VINIT   => $prefix . 'я',
                    self::TVORIT  => $prefix . 'ем',
                    self::PREDLOJ => $prefix . 'е',
                ];
            }
        }

        return array_fill_keys(CasesHelper::allCases(), StringHelper::title($name));
    }
}
