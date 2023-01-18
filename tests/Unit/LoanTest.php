<?php

namespace Tests\Unit;

use App\Models\ValueObjects\Amount;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Tests\Traits\DummyLoans;

class LoanTest extends TestCase
{
    use DummyLoans;

    /**
     * @test
     */
    public function it_cannot_repay_more_than_owed()
    {
        $this->expectException(InvalidArgumentException::class);

        $loan = $this->createLoan(
            amountOwed: Amount::make(1000)
        );

        $repayment = Amount::make(1200);

        $loan->repay($repayment);
    }

    /**
     * @test
     */
    public function it_can_determines_if_is_paid()
    {
        $loan = $this->createLoan(
            amountOwed: Amount::make(1000)
        );

        $this->assertFalse($loan->isPaid());
        $this->assertEquals('ACTIVE', $loan->getState());

        $repaymentOne = Amount::make(500);

        $loan->repay($repaymentOne);

        $this->assertFalse($loan->isPaid());
        $this->assertEquals('ACTIVE', $loan->getState());

        $repaymentTwo = Amount::make(500);

        $loan->repay($repaymentTwo);

        $this->assertTrue($loan->isPaid());
        $this->assertEquals('PAID', $loan->getState());
    }

    /**
     * @test
     */
    public function it_determines_if_overpaid()
    {
        $loan = $this->createLoan(
            amountOwed: Amount::make(1000)
        );

        $this->assertFalse($loan->isOverpaid(Amount::make(500)));

        $this->assertTrue($loan->isOverpaid(Amount::make(1200)));
    }
}
