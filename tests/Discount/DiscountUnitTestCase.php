<?php

namespace Kata\Tests\Discount;

use Kata\Discount\Domain\Product\ProductDiscounter;
use Kata\Discount\Domain\Product\ProductRepository;
use Kata\Tests\Shared\Infrastructure\PhpUnit\UnitTestCase;
use PHPUnit\Framework\MockObject\MockObject;

abstract class DiscountUnitTestCase extends UnitTestCase
{
    private ProductRepository|MockObject|null $productRepository;
    private ProductDiscounter|MockObject|null $productDiscounter;

    protected function productRepository(): ProductRepository|MockObject
    {
        return $this->productRepository = $this->productRepository ?? $this->createMock(ProductRepository::class);
    }

    protected function productDiscounter(): ProductDiscounter|MockObject
    {
        return $this->productDiscounter = $this->productDiscounter ?? $this->createMock(ProductDiscounter::class);
    }
}
