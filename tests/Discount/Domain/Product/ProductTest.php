<?php

namespace Kata\Tests\Discount\Domain\Product;

use Kata\Common\Domain\Contracts\Entity;
use Kata\Discount\Domain\Product\Product;
use Kata\Tests\Discount\DiscountUnitTestCase;

class ProductTest extends DiscountUnitTestCase
{
    public const SKU = '000000';
    private ?Product $sut = null;

    protected function setUp(): void
    {
        $this->sut = Product::create(
            sku: self::SKU,
            name: 'fake',
            category: 'fake',
            price: 10000
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
            'equals' => [self::SKU, 'fake_name', 'fake', 10000, true],
            'not equals' => ['00001', 'fake_name', 'fake', 10000, false],
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
        $price = 10000;

        return [
            10 => [10, $price, ($price - 1000)],
            15 => [15, $price, ($price - 1500)],
            30 => [30, $price, ($price - 3000)],
        ];
    }

    /** @dataProvider discountProvider */
    public function testItShouldCalculateAnyDiscountPercentage(int $discount, int $originalExpected, int $finalExpected)
    {
        $this->sut->applyDiscount($discount);

        $this->assertEquals($discount, $this->sut->price()->discountPercentage());
        $this->assertEquals($originalExpected, $this->sut->price()->original());
        $this->assertEquals($finalExpected, $this->sut->price()->final());
    }
}
