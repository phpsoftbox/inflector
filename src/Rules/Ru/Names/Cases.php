<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Rules\Ru\Names;

use PhpSoftBox\Inflector\Names\Cases as BaseCases;

interface Cases
{
    public const IMENIT  = BaseCases::NOMINATIVE->value;
    public const RODIT   = BaseCases::GENITIVE->value;
    public const DAT     = BaseCases::DATIVE->value;
    public const VINIT   = BaseCases::ACCUSATIVE->value;
    public const TVORIT  = BaseCases::ABLATIVE->value;
    public const PREDLOJ = BaseCases::PREPOSITIONAL->value;
}
