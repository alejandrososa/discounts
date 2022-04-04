<?php

namespace Kata\Tests\Common\Domain\Model;

use Kata\Common\Domain\Model\Currency;
use Kata\Common\Domain\Model\Price;
use Kata\Tests\Shared\Domain\Creator;

class PriceMother
{
    public static function create(?int $price = null, ?string $currency = null): Price
    {
        return Price::fromInt(
            $price ?? Creator::random()->randomNumber(5, true),
            new Currency($currency ?? Currency::DEFAULT_CODE)
        );
    }
}
