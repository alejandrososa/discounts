<?php

namespace Kata\Tests\Discount\Domain\Discount\Specification;

use Kata\Discount\Domain\Discount\Discounter;
use Kata\Discount\Domain\Discount\Specification\CategorySpecification;
use Kata\Discount\Domain\Discount\Specification\SkuSpecification;
use Kata\Discount\Domain\Product\ProductDiscounter;
use Kata\Tests\Discount\DiscountUnitTestCase;
use Kata\Tests\Discount\Domain\Product\ProductMother;

class DiscounterTest extends DiscountUnitTestCase
{
    public function testItMustBeInstanceOfProductDiscounter()
    {
        $this->assertInstanceOf(ProductDiscounter::class, new Discounter());
    }

    public function categoryProvider()
    {
        return [
            'children'  => ['10000', 'babies', '10005', 'toys', false],
            'women'     => ['10000', 'shoes', '10000', 'shoes', true],
            'men'       => ['10000', 'suits', '10000', 'suits', true],
        ];
    }

    /** @dataProvider categoryProvider */
    public function testItMustIterateOverCollectionOfSpecificationToApplyDiscountWhenIsNecessary(
        string $skuCondition,
        string $categoryCondition,
        string $productSku,
        string $productCategory,
        bool $validationExpected
    ) {
        $product = ProductMother::create(sku: $productSku, category: $productCategory);

        $collection = SpecificationCollectionMother::create([
            OrSpecificationMother::create(
                new SkuSpecification($skuCondition),
                new CategorySpecification($categoryCondition),
            )
        ]);

        $sut = new Discounter($collection);
        $sut->apply($product);

        $this->assertEquals($validationExpected, $product->price()->original() !== $product->price()->final());
        $this->assertEquals($validationExpected, !is_null($product->price()->discountPercentage()));
    }
}
