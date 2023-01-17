<?php

namespace App\Models;

use App\Models\ValueObjects\Amount;
use App\Models\ValueObjects\LoanNumber;
use App\Types\AmountType;
use App\Types\LoanNumberType;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use InvalidArgumentException;
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Entity]
#[Table('loans')]
class Loan
{
    #[Id]
    #[Column(type: UuidType::NAME, unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string $id;

    public function __construct(
        #[Column(name: 'customer_id', type: UuidType::NAME)]
        private UuidInterface $customerId,
        #[Column(name: 'reference', type: LoanNumberType::NAME)]
        private LoanNumber $loanNumber,
        #[Column]
        private string $state,
        #[Column(name: 'amount_issued', type: AmountType::NAME)]
        private Amount $amountIssued,
        #[Column(name: 'amount_owed', type: AmountType::NAME)]
        private Amount $amountOwed
    ) {}

    public function isOverpaid(Amount $amount): bool
    {
        return $this->amountOwed->lt($amount);
    }

    public function isPaid(): bool
    {
        return $this->amountOwed->getAmount() === 0;
    }

    public function repay(Amount $amount): void
    {
        if ($this->amountOwed->lt($amount)) {
            throw new InvalidArgumentException('Cannot repay more than owed');
        }

        $this->amountOwed = $this->amountOwed->subtract($amount);

        if ($this->isPaid()) {
            $this->state = 'PAID';
        }
    }

    public function setId(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCustomerId(): UuidInterface
    {
        return $this->customerId;
    }

    public function getLoanNumber(): LoanNumber
    {
        return $this->loanNumber;
    }

    public function getState(): string
    {
        return $this->state;
    }

    public function getAmountIssued(): Amount
    {
        return $this->amountIssued;
    }

    public function getAmountOwed(): Amount
    {
        return $this->amountOwed;
    }

    public static function make(array $attributes): self
    {
        $loan = new self(
            customerId: Uuid::fromString($attributes['customerId']),
            loanNumber: LoanNumber::make($attributes['reference']),
            state: $attributes['state'],
            amountIssued: Amount::make($attributes['amount_issued'] * 100),
            amountOwed: Amount::make($attributes['amount_to_pay'] * 100),
        );

        $loan->setId(Uuid::fromString($attributes['id']));

        return $loan;
    }
}