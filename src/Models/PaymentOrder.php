<?php

namespace App\Models;

use App\Models\ValueObjects\Amount;
use App\Types\AmountType;
use App\Types\CarbonType;
use Carbon\Carbon;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use http\Exception\InvalidArgumentException;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;

#[Entity]
#[Table('payment_orders')]
class PaymentOrder
{
    #[Id]
    #[Column(type: UuidType::NAME, unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string $id;

    #[Column(name: 'ref_id', type: UuidType::NAME, unique: true)]
    private ?UuidInterface $refId = null;

    #[Column]
    private PaymentOrderStatus $status = PaymentOrderStatus::ORDERED;

    public function __construct(
        #[Column(name: 'loan_id', type: UuidType::NAME, unique: false)]
        private UuidInterface $loanId,
        #[Column]
        private string $firstname,
        #[Column]
        private string $lastname,
        #[Column(name: 'payment_date', type: CarbonType::NAME)]
        private Carbon $paymentDate,
        #[Column(type: AmountType::NAME)]
        private Amount $amount,
        #[Column]
        private string $description
    ) {}

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getLoanId(): UuidInterface
    {
        return $this->loanId;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getPaymentDate(): Carbon
    {
        return $this->paymentDate;
    }

    public function getAmount(): Amount
    {
        return $this->amount;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getRefId(): ?UuidInterface
    {
        return $this->refId;
    }

    public function getStatus(): PaymentOrderStatus
    {
        return $this->status;
    }

    public function setRefId(UuidInterface $refId): self
    {
        if (is_null($this->refId)) {
            $this->refId = $refId;
        }

        return $this;
    }

    public function setStatus(PaymentOrderStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public static function makeFromPayment(Payment $payment, Amount $amount, string $description): self
    {
        if (is_null($payment->getLoanId())) {
            throw new InvalidArgumentException('Payment order can be created only from assigned payments');
        }

        return new self(
            loanId: $payment->getLoanId(),
            firstname: $payment->getFirstname(),
            lastname: $payment->getLastname(),
            paymentDate: Carbon::now(),
            amount: $amount,
            description: $description,
        );
    }
}