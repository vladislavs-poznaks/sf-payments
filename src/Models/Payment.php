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
use Ramsey\Uuid\Doctrine\UuidGenerator;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

#[Entity]
#[Table('payments')]
class Payment
{
    #[Id]
    #[Column(type: UuidType::NAME, unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private UuidInterface|string $id;

    #[Column(name: 'loan_id', type: UuidType::NAME, unique: false)]
    private ?UuidInterface $loanId = null;

    #[Column]
    private PaymentStatus $status = PaymentStatus::RECEIVED;

    public function __construct(
        #[Column]
        private string $firstname,
        #[Column]
        private string $lastname,
        #[Column(name: 'payment_date', type: CarbonType::NAME)]
        private Carbon $paymentDate,
        #[Column(type: AmountType::NAME)]
        private Amount $amount,
        #[Column]
        private string $description,
        #[Column(name: 'ref_id', type: UuidType::NAME, unique: true)]
        private UuidInterface $refId
    ) {}

    public function setId(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getLoanId(): ?UuidInterface
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

    public function getRefId(): UuidInterface
    {
        return $this->refId;
    }

    public function getStatus(): PaymentStatus
    {
        return $this->status;
    }

    public function setStatus(PaymentStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function setLoan(Loan $loan): self
    {
        if (is_null($this->loanId)) {
            $this->loanId = $loan->getId();
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId()->toString(),
            'loanId' => $this->getLoanId()?->toString(),
            'firstname' => $this->getFirstname(),
            'lastname' => $this->getLastname(),
            'paymentDate' => $this->getPaymentDate()->toDateTimeString(),
            'amount' => $this->getAmount()->getAmount(),
            'description' => $this->getDescription(),
            'refId' => $this->getRefId()->toString(),
            'status' => $this->getStatus()->toString(),
        ];
    }

    public static function make(array $attributes): self
    {
        $firstname = $attributes['firstname'];
        $lastname = $attributes['lastname'];
        $paymentDate = Carbon::parse($attributes['paymentDate']);
        $amount = Amount::make(ceil($attributes['amount'] * 100));
        $description = $attributes['description'];
        $refId = Uuid::fromString($attributes['refId']);

        return new self(
            firstname: $firstname,
            lastname: $lastname,
            paymentDate: $paymentDate,
            amount: $amount,
            description: $description,
            refId: $refId
        );
    }
}