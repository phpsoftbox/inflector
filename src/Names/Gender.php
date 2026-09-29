<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Names;

use PhpSoftBox\Inflector\Contracts\InflectorInterface;

/**
 * Пол для склонения ФИО: передаётся явно или определяется {@see InflectorInterface::detectNameGender()}.
 */
enum Gender: string
{
    case MALE   = 'm';
    case FEMALE = 'f';
}
