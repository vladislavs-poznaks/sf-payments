<?php

namespace Tests\Unit;

use App\Models\Payment;
use App\Models\ValueObjects\Amount;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Tests\Traits\DummyLoans;

class PaymentTest extends TestCase
{
    use DummyLoans;

    /**
     * @test
     */
    public function it_cannot_reset_loan_if_set_already()
    {
        $payment = new Payment(
            firstname: 'Testfirst',
            lastname: 'Testlast',
            paymentDate: Carbon::now(),
            amount: Amount::make(1000),
            description: 'Test',
            refId: Uuid::uuid4()
        );

        $this->assertNull($payment->getLoanId());

        $firstLoanId = Uuid::uuid4();
        $firstLoan = $this->createLoan($firstLoanId);

        $secondLoanId = Uuid::uuid4();
        $secondLoan = $this->createLoan($secondLoanId);

        $payment->setLoan($firstLoan);

        $this->assertEquals($firstLoanId, $payment->getLoanId());

        $payment->setLoan($secondLoan);

        $this->assertNotEquals($secondLoanId, $payment->getLoanId());
        $this->assertEquals($firstLoanId, $payment->getLoanId());
    }
}
