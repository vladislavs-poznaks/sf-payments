<?php

namespace Tests\Unit;

use App\Models\ValueObjects\Amount;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AmountTest extends TestCase
{
    /**
     * @test
     * @dataProvider getInvalidAmounts
     */
    public function it_throws_an_exception_if_invalid_amount($amount)
    {
        $this->expectException(InvalidArgumentException::class);

        new Amount($amount);
    }

    /**
     * @test
     */
    public function it_can_be_created_with_a_factory_method()
    {
        $amount = 100;

        $this->assertEquals($amount, Amount::make($amount)->getAmount());
    }

    /**
     * @test
     */
    public function it_can_compare_two_amounts()
    {
        $amountOfFive = Amount::make(5);
        $amountOfTen = Amount::make(10);

        $this->assertTrue($amountOfFive->lt($amountOfTen));
        $this->assertFalse($amountOfTen->lt($amountOfFive));
    }

    /**
     * @test
     */
    public function it_can_create_a_subtracted_amount()
    {
        $amountOfThree = Amount::make(3);
        $amountOfTen = Amount::make(10);

        $amount = $amountOfTen->subtract($amountOfThree);

        $this->assertEquals(7, $amount->getAmount());
    }

    public function getInvalidAmounts()
    {
        return [
            [-10],
        ];
    }
}
