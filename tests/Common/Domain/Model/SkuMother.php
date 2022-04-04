<?php

namespace Kata\Tests\Common\Domain\Model;

use Kata\Common\Domain\Model\Sku;
use Kata\Tests\Shared\Domain\TextMother;

class SkuMother
{
    public static function create(?string $sku = null): Sku
    {
        return new Sku($sku ?? TextMother::create());
    }
}
