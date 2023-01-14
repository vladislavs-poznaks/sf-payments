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
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Doctrine\UuidType;
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
}