<?php

namespace Kata\Tests\Common\Domain\Model;

use Kata\Common\Domain\Model\Currency;
use Kata\Common\Domain\Model\Price;
use Kata\Tests\Common\CommonUnitTestCase;

class PriceTest extends CommonUnitTestCase
{
    private ?int $price = null;
    private ?Currency $currency = null;

    protected function setUp(): void
    {
        $this->price = $this->fake()->randomNumber(5, true);
        $this->currency = Currency::fromString($this->fake()->currencyCode());
    }

    protected function tearDown(): void
    {
        $this->price = null;
        $this->currency = null;
    }

    public function testItShouldCreatePriceFromInt()
    {
        $price = Price::fromInt($this->price, $this->currency);

        $this->assertEquals($this->price, $price->original());
        $this->assertEquals($this->price, $price->final());
    }

    public function testItShouldCreatePriceFromMoney()
    {
        $priceObjectValue = PriceMother::create(price: $this->price);
        $price = Price::fromMoney($priceObjectValue);

        $this->assertEquals($this->price, $price->original());
        $this->assertEquals($this->price, $price->final());
        $this->assertTrue($price->equals($priceObjectValue));
    }

    public function testItShouldAddValueToPrice()
    {
        $otherPrice = Price::fromInt($this->price, $this->currency);
        $price = Price::fromInt($this->price, $this->currency);
        $priceExpected = $this->price + $this->price;

        $this->assertEquals($priceExpected, $price->add($otherPrice)->original());
    }

    public function testItShouldSubtractValueToPrice()
    {
        $otherPrice = Price::fromInt($this->price, $this->currency);
        $price = Price::fromInt($this->price, $this->currency);
        $priceExpected = $this->price - $this->price;

        $this->assertEquals($priceExpected, $price->subtract($otherPrice)->original());
    }

    public function discountProvider()
    {
        return [
            '10 percent' => [10, 10000, 9000],
            '20 percent' => [20, 10000, 8000],
            '50 percent' => [50, 10000, 5000],
            '75 percent' => [75, 10000, 2500],
        ];
    }

    /** @dataProvider discountProvider */
    public function testItShouldApplyPercentDiscount(int $discount, int $price, int $priceFinalExpected)
    {
        $currentPrice = Price::fromInt($price, $this->currency);
        $newPrice = $currentPrice->applyPercentDiscount($discount);

        $this->assertEquals($price, $newPrice->original());
        $this->assertNotEquals($price, $newPrice->final());
        $this->assertEquals($priceFinalExpected, $newPrice->final());
        $this->assertNotNull($newPrice->discountPercentage());
    }

    public function testItShouldConvertToArray()
    {
        $price = Price::fromInt($this->price, $this->currency);
        $arrayExpected = [
            'original' => $this->price,
            'final' => $this->price,
            'discount_percentage' => null,
            'currency' => $this->currency->code(),
        ];

        $this->assertIsArray($price->toArray());
        $this->assertSame($arrayExpected, $price->toArray());
    }

    /**
     * @test
     * @depends testItShouldCreatePriceFromInt
     */
    public function testItCanBeCompared()
    {
        $anotherPrice = $this->fake()->randomNumber(5, true);

        $first = Price::fromInt($this->price, $this->currency);
        $second = Price::fromInt($this->price, $this->currency);
        $third = Price::fromInt($anotherPrice, $this->currency);

        $this->assertTrue($first->equals($second));
        $this->assertFalse($first->equals($third));
    }
}
