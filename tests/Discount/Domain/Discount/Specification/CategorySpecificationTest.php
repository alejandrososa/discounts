<?php

namespace Kata\Tests\Discount\Domain\Discount\Specification;

use Kata\Discount\Domain\Discount\Specification\CategorySpecification;
use Kata\Discount\Domain\Discount\Specification\OrSpecification;
use Kata\Discount\Domain\Discount\Specification\Specification;
use Kata\Tests\Discount\DiscountUnitTestCase;
use Kata\Tests\Discount\Domain\Product\ProductMother;
use Kata\Tests\Shared\Domain\TextMother;

class CategorySpecificationTest extends DiscountUnitTestCase
{
    public function testItMustBeInstanceOfSpecification()
    {
        $this->assertInstanceOf(Specification::class, new CategorySpecification(TextMother::create()));
    }

    public function categoryProvider()
    {
        $discount = CategorySpecification::DISCOUNT_PERCENTAGE;

        return [
            'children' => ['girls', 'babies', 'men category', null, false],
            'women' => ['dresses', 'shoes', 'shoes', $discount, true],
            'men' => ['pants', 'shirts', 'suits', null, false],
        ];
    }

    /** @dataProvider categoryProvider */
    public function testItSatisfiedBy(
        string $firstCondition,
        string $secondCondition,
        string $productCategory,
        ?int $discountExpected,
        bool $validationExpected
    ) {
        $product = ProductMother::create(category: $productCategory);
        $spec1 = new CategorySpecification($firstCondition);
        $spec2 = new CategorySpecification($secondCondition);

        $orSpec = new OrSpecification($spec1, $spec2);

        $this->assertEquals($validationExpected, $orSpec->isSatisfiedBy($product));
        $this->assertEquals($discountExpected, $orSpec->discountToApply()->amount());

    }
}
