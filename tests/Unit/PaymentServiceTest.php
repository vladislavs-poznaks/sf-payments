<?php

namespace Tests\Unit;

use App\Models\PaymentStatus;
use App\Models\ValueObjects\Amount;
use App\Services\Exceptions\PaymentServiceException;
use App\Services\PaymentService;
use PHPUnit\Framework\TestCase;
use Tests\FakeRepositories\FakeLoansRepository;
use Tests\FakeRepositories\FakePaymentOrdersRepository;
use Tests\FakeRepositories\FakePaymentsRepository;
use Tests\Traits\DummyLoans;
use Tests\Traits\DummyPayments;

class PaymentServiceTest extends TestCase
{
    use DummyLoans, DummyPayments;

    /**
     * @test
     */
    public function it_throws_an_exception_if_loan_number_missing_and_sets_relevant_payment_status()
    {
        $this->expectException(PaymentServiceException::class);

        $payment = $this->createPayment(
            description: 'Test description with no loan number'
        );

        $service = new PaymentService(
            new FakeLoansRepository(),
            new FakePaymentsRepository(),
            new FakePaymentOrdersRepository(),
        );

        $service->handle($payment);

        $this->assertEquals(PaymentStatus::MISSING_LOAN_NUMBER_ERROR, $service->getPayment()->getStatus());

        $this->assertGreaterThanOrEqual(1, $service->getPaymentsRepository()->getMethodCalls()['sync']);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_multiple_loan_numbers_and_sets_relevant_payment_status()
    {
        $this->expectException(PaymentServiceException::class);

        $payment = $this->createPayment(
            description: 'Test description with LN12345678 and LN87654321'
        );

        $service = new PaymentService(
            new FakeLoansRepository(),
            new FakePaymentsRepository(),
            new FakePaymentOrdersRepository(),
        );

        $service->handle($payment);

        $this->assertEquals(PaymentStatus::MULTIPLE_LOAN_NUMBER_ERROR, $service->getPayment()->getStatus());

        $this->assertGreaterThanOrEqual(1, $service->getPaymentsRepository()->getMethodCalls()['sync']);
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_cannot_find_a_loan_by_loan_number_and_sets_relevant_payment_status()
    {
        $this->expectException(PaymentServiceException::class);

        $payment = $this->createPayment(
            description: 'Test description with correct loan number LN12345678'
        );

        $loansRepository = new FakeLoansRepository();
        $loansRepository->setReturnLoan(null);

        $service = new PaymentService(
            $loansRepository,
            new FakePaymentsRepository(),
            new FakePaymentOrdersRepository(),
        );

        $service->handle($payment);

        $this->assertEquals(PaymentStatus::INCORRECT_LOAN_NUMBER_ERROR, $service->getPayment()->getStatus());

        $this->assertGreaterThanOrEqual(1, $service->getPaymentsRepository()->getMethodCalls()['sync']);
    }

    /**
     * @test
     */
    public function it_assigns_payment_if_payment_does_not_cover_the_debt()
    {
        $loan = $this->createLoan(
            amountIssued: Amount::make(1000),
            amountOwed: Amount::make(1200),
        );

        $payment = $this->createPayment(
            description: 'Test description with correct loan number LN12345678',
            amount: Amount::make(1000),
        );

        $loansRepository = new FakeLoansRepository();
        $loansRepository->setReturnLoan($loan);

        $service = new PaymentService(
            $loansRepository,
            new FakePaymentsRepository(),
            new FakePaymentOrdersRepository(),
        );

        $service->handle($payment);

        $this->assertEquals(PaymentStatus::ASSIGNED, $service->getPayment()->getStatus());

        $this->assertGreaterThanOrEqual(1, $service->getPaymentsRepository()->getMethodCalls()['sync']);
    }

    /**
     * @test
     */
    public function it_assigns_payment_partially_if_payment_exceeds_the_debt()
    {
        $loan = $this->createLoan(
            amountIssued: Amount::make(1000),
            amountOwed: Amount::make(1200),
        );

        $payment = $this->createPayment(
            description: 'Test description with correct loan number LN12345678',
            amount: Amount::make(5000),
        );

        $loansRepository = new FakeLoansRepository();
        $loansRepository->setReturnLoan($loan);

        $service = new PaymentService(
            $loansRepository,
            new FakePaymentsRepository(),
            new FakePaymentOrdersRepository(),
        );

        $service->handle($payment);

        $this->assertEquals(PaymentStatus::PARTIALLY_ASSIGNED, $service->getPayment()->getStatus());

        $this->assertGreaterThanOrEqual(1, $service->getPaymentsRepository()->getMethodCalls()['sync']);
    }

    /**
     * @test
     */
    public function it_reduces_the_debt_amount_if_paid_partially()
    {
        $loan = $this->createLoan(
            amountIssued: Amount::make(1000),
            amountOwed: Amount::make(1200),
        );

        $payment = $this->createPayment(
            description: 'Test description with correct loan number LN12345678',
            amount: Amount::make(1000),
        );

        $loansRepository = new FakeLoansRepository();
        $loansRepository->setReturnLoan($loan);

        $service = new PaymentService(
            $loansRepository,
            new FakePaymentsRepository(),
            new FakePaymentOrdersRepository(),
        );

        $service->handle($payment);

        $this->assertEquals(200, $service->getLoansRepository()->getSyncedLoan()->getAmountOwed()->getAmount());

        $this->assertEquals('ACTIVE', $service->getLoansRepository()->getSyncedLoan()->getState());

        $this->assertGreaterThanOrEqual(1, $service->getLoansRepository()->getMethodCalls()['sync']);
    }

    /**
     * @test
     */
    public function it_reduces_the_debt_amount_if_paid_fully()
    {
        $loan = $this->createLoan(
            amountIssued: Amount::make(1000),
            amountOwed: Amount::make(1200),
        );

        $payment = $this->createPayment(
            description: 'Test description with correct loan number LN12345678',
            amount: Amount::make(5000),
        );

        $loansRepository = new FakeLoansRepository();
        $loansRepository->setReturnLoan($loan);

        $service = new PaymentService(
            $loansRepository,
            new FakePaymentsRepository(),
            new FakePaymentOrdersRepository(),
        );

        $service->handle($payment);

        $this->assertEquals(0, $service->getLoansRepository()->getSyncedLoan()->getAmountOwed()->getAmount());

        $this->assertEquals('PAID', $service->getLoansRepository()->getSyncedLoan()->getState());

        $this->assertGreaterThanOrEqual(1, $service->getLoansRepository()->getMethodCalls()['sync']);
    }

    /**
     * @test
     */
    public function it_creates_payment_order_if_overpaid()
    {
        $loan = $this->createLoan(
            amountIssued: Amount::make(1000),
            amountOwed: Amount::make(1200),
        );

        $payment = $this->createPayment(
            description: 'Test description with correct loan number LN12345678',
            amount: Amount::make(2000),
        );

        $loansRepository = new FakeLoansRepository();
        $loansRepository->setReturnLoan($loan);

        $service = new PaymentService(
            $loansRepository,
            new FakePaymentsRepository(),
            new FakePaymentOrdersRepository(),
        );

        $service->handle($payment);

        $this->assertEquals(800, $service->getPaymentOrdersRepository()->getSyncedPaymentOrder()->getAmount()->getAmount());

        $description = $service->getPaymentOrdersRepository()->getSyncedPaymentOrder()->getDescription();
        $this->assertTrue(str_contains($description, 'LN12345678'));

        $this->assertEquals($loan->getId(), $service->getPaymentOrdersRepository()->getSyncedPaymentOrder()->getLoanId());

        $this->assertGreaterThanOrEqual(1, $service->getPaymentOrdersRepository()->getMethodCalls()['persist']);
        $this->assertGreaterThanOrEqual(1, $service->getPaymentOrdersRepository()->getMethodCalls()['sync']);
    }
}