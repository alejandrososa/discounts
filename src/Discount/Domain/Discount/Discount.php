<?php

namespace Kata\Discount\Domain\Discount;

class Discount
{
    public function __construct(private ?int $amount = null)
    {
    }

    public function amount(): ?int
    {
        return $this->amount;
    }
}
