<?php

namespace Kata\Tests\Discount\Domain\Product;

use Kata\Common\Domain\Contracts\Entity;
use Kata\Discount\Domain\Product\Product;
use Kata\Tests\Common\Domain\Model\PriceMother;
use Kata\Tests\Common\Domain\Model\SkuMother;
use Kata\Tests\Discount\DiscountUnitTestCase;
use Kata\Tests\Shared\Domain\TextMother;

class ProductTest extends DiscountUnitTestCase
{
    public const SKU = '000000';
    public const PRICE = 10000;
    private ?Product $sut = null;

    protected function setUp(): void
    {
        $this->sut = Product::create(
            sku: SkuMother::create(self::SKU),
            name: TextMother::create(),
            category: TextMother::create(),
            price: PriceMother::create()->original()
        );
    }

    protected function tearDown(): void
    {
        $this->sut = null;
    }

    public function testItMustBeInstanceOfEntity()
    {
        $this->assertInstanceOf(Entity::class, $this->sut);
    }

    public function testItMustChangeToArray()
    {
        $this->assertIsArray($this->sut->toArray());
    }

    public function productProvider()
    {
        return [
            'equals' => [self::SKU, 'fake_name', 'fake', self::PRICE, true],
            'not equals' => ['00001', 'fake_name', 'fake', self::PRICE, false],
        ];
    }

    /** @dataProvider productProvider */
    public function testItMustValidateIfIsEqualToAnotherObject(
        string $sku,
        string $name,
        string $category,
        int $price,
        bool $expected,
    ) {
        $product = Product::create($sku, $name, $category, $price);
        $this->assertEquals($expected, $this->sut->equals($product));
    }

    public function discountProvider()
    {
        return [
            '10% discount' => [10],
            '15% discount' => [15],
            '30% discount' => [30],
        ];
    }

    /** @dataProvider discountProvider */
    public function testItShouldCalculateAnyDiscountPercentage(int $discount)
    {
        $this->sut->applyDiscount($discount);

        $this->assertNotNull($this->sut->price()->discountPercentage());
    }
}
