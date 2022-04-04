<?php

namespace Kata\Tests\Common\Domain\Model;

use Kata\Common\Domain\Model\Currency;
use Kata\Tests\Common\CommonUnitTestCase;

class CurrencyTest extends CommonUnitTestCase
{
    private ?string $currencyCode = null;

    protected function setUp(): void
    {
        $this->currencyCode = $this->fake()->currencyCode();
    }

    protected function tearDown(): void
    {
        $this->currencyCode = null;
    }

    public function testItShouldCreateCurrencyCodeFromString()
    {
        $currencyCode = Currency::fromString($this->currencyCode);

        $this->assertSame($this->currencyCode, $currencyCode->code());
        $this->assertSame($this->currencyCode, (string)$currencyCode);
    }

    public function testItShouldGeneratedCurrencyCodeValid()
    {
        $currencyCode = Currency::generate();
        $this->assertEquals(Currency::DEFAULT_CODE, $currencyCode->code());
    }

    /**
     * @test
     * @depends testItShouldCreateCurrencyCodeFromString
     */
    public function testItCanBeCompared()
    {
        $anotherCurrencyCode = $this->fake()->currencyCode();

        $first = Currency::fromString($this->currencyCode);
        $second = Currency::fromString($this->currencyCode);
        $third = Currency::fromString($anotherCurrencyCode);

        $this->assertTrue($first->equals($second));
        $this->assertFalse($first->equals($third));
    }
}
