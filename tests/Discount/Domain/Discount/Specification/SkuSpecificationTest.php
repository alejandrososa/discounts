<?php

namespace Kata\Tests\Discount\Domain\Discount\Specification;

use Kata\Discount\Domain\Discount\Specification\OrSpecification;
use Kata\Discount\Domain\Discount\Specification\SkuSpecification;
use Kata\Discount\Domain\Discount\Specification\Specification;
use Kata\Tests\Discount\DiscountUnitTestCase;
use Kata\Tests\Discount\Domain\Product\ProductMother;
use Kata\Tests\Shared\Domain\TextMother;

class SkuSpecificationTest extends DiscountUnitTestCase
{
    public function testItMustBeInstanceOfSpecification()
    {
        $this->assertInstanceOf(Specification::class, new SkuSpecification(TextMother::create()));
    }

    public function skuProvider()
    {
        $discount = SkuSpecification::DISCOUNT_PERCENTAGE;
        return [
            '00001' => ['00001', '00003', '00007', null, false],
            '10001' => ['10001', '12010', '10001', $discount,  true],
            '77777' => ['77777', '88888', '99999', null, false],
        ];
    }

    /** @dataProvider skuProvider */
    public function testItSatisfiedBy(
        string $firstCondition,
        string $secondCondition,
        string $sku,
        ?int $discountExpected,
        bool $validationExpected
    ) {
        $product = ProductMother::create(sku: $sku);
        $spec1 = new SkuSpecification($firstCondition);
        $spec2 = new SkuSpecification($secondCondition);
        $orSpec = new OrSpecification($spec1, $spec2);

        $this->assertEquals($validationExpected, $orSpec->isSatisfiedBy($product));
        $this->assertEquals($discountExpected, $orSpec->discountToApply()->amount());
    }
}
