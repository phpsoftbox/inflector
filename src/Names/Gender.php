<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Names;

enum Gender: string
{
    case MALE   = 'm';
    case FEMALE = 'f';
    case NEUTER = 'n';
}
