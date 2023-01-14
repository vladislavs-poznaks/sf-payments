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

    public function getInvalidAmounts()
    {
        return [
            [-10],
            [0],
        ];
    }
}