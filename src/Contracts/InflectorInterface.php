<?php

declare(strict_types=1);

namespace PhpSoftBox\Inflector\Contracts;

interface InflectorInterface
{
    public function pluralize(string $word): string;

    public function singularize(string $word): string;
}
