<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Payment;
use App\Models\PaymentOrder;
use App\Models\PaymentStatus;
use App\Models\ValueObjects\LoanNumber;
use App\Parsers\Exceptions\MissingLoanNumberParserException;
use App\Parsers\Exceptions\MultipleLoanNumbersParserException;
use App\Parsers\LoanNumberParser;
use App\Repositories\LoansDatabaseRepository;
use App\Repositories\PaymentOrdersDatabaseRepository;
use App\Repositories\PaymentsDatabaseRepository;
use App\Services\Exceptions\PaymentServiceException;

class PaymentService
{
    private ?Payment $payment = null;

    private LoansDatabaseRepository $loansRepository;
    private PaymentsDatabaseRepository $paymentsRepository;
    private PaymentOrdersDatabaseRepository $paymentOrdersRepository;

    public function __construct()
    {
        $this->loansRepository = new LoansDatabaseRepository();
        $this->paymentsRepository = new PaymentsDatabaseRepository();
        $this->paymentOrdersRepository = new PaymentOrdersDatabaseRepository();
    }

    /**
     * @throws PaymentServiceException
     */
    public function handle(Payment $payment): void
    {
        $this->setPayment($payment);

        $loanNumber = $this->getLoanNumber();

        $this->processPayment($this->getLoan($loanNumber));
    }

    public function setPayment(Payment $payment)
    {
        $this->payment = $payment;

        $this->paymentsRepository->persist($this->payment);
        $this->paymentsRepository->sync($this->payment);
    }

    public function getPayment(): Payment
    {
        return $this->payment;
    }

    private function getLoanNumber(): LoanNumber
    {
        try {
            return (new LoanNumberParser())($this->payment->getDescription());
        } catch (MissingLoanNumberParserException) {
            $this->changePaymentStatus(PaymentStatus::MISSING_LOAN_NUMBER_ERROR);
        } catch (MultipleLoanNumbersParserException) {
            $this->changePaymentStatus(PaymentStatus::MULTIPLE_LOAN_NUMBER_ERROR);
        }

        throw new PaymentServiceException("Failed to process loan number");
    }

    private function getLoan(LoanNumber $loanNumber): Loan
    {
        $loan = $this->loansRepository->getByLoanNumber($loanNumber);

        if (is_null($loan)) {
            $this->changePaymentStatus(PaymentStatus::INCORRECT_LOAN_NUMBER_ERROR);
            throw new PaymentServiceException("Failed to find loan by parsed loan number");
        }

        return $loan;
    }

    private function processPayment(Loan $loan): void
    {
        $this->payment->setLoan($loan);
        $this->changePaymentStatus(PaymentStatus::ASSIGNED);

        $coveredAmount = $this->payment->getAmount();

        if ($loan->isOverpaid($this->payment->getAmount())) {
            $coveredAmount = $loan->getAmountOwed();

            $overpaidAmount = $this->payment->getAmount()->subtract($loan->getAmountOwed());

            $paymentOrder = PaymentOrder::makeFromPayment(
                $this->payment,
                $overpaidAmount,
                'Overpaid amount repayment for loan: ' . $loan->getLoanNumber(),
            );

            $this->paymentOrdersRepository->persist($paymentOrder);

            $this->paymentOrdersRepository->sync($paymentOrder);
        }

        $loan->repay($coveredAmount);

        $this->loansRepository->sync($loan);
        $this->changePaymentStatus(PaymentStatus::PROCESSED);
    }

    private function changePaymentStatus(PaymentStatus $status)
    {
        $this->payment->setStatus($status);
        $this->paymentsRepository->sync($this->payment);
    }
}