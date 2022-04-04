<?php

namespace Kata\Tests\Common\Domain\Model;

use Kata\Common\Domain\Contracts\Currency;
use Kata\Common\Domain\Model\Sku;

class CurrencyMother
{
    public static function create(?string $currency = null): Sku
    {
        return new Sku($currency ?? Currency::DEFAULT_CODE);
    }
}
