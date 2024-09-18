<?php

namespace CodingWisely\Commitvel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \CodingWisely\Commitvel\Commitvel
 */
class Commitvel extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \CodingWisely\Commitvel\Commitvel::class;
    }
}
