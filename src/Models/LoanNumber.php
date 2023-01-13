<?php

namespace App\Models;

use InvalidArgumentException;

class LoanNumber
{
    public const PREFIX = 'LN';
    public const NUMBER_COUNT = 8;

    private string $loanNumber;

    public function __construct(string $loanNumber)
    {
        if (!static::isValid($loanNumber)) {
            $prefix = static::PREFIX;
            $count = static::NUMBER_COUNT;

            throw new InvalidArgumentException("Loan number starts with {$prefix} followed by {$count} numbers");
        }

        $this->loanNumber = $loanNumber;
    }

    public function __toString(): string
    {
        return $this->loanNumber;
    }

    public static function isValid(string $loanNumber): bool
    {
        $prefix = static::PREFIX;
        $count = static::NUMBER_COUNT;

        return (bool) preg_match("/($prefix)[0-9]{{$count},{$count}}/", $loanNumber);
    }

    public static function make(string $loanNumber): self
    {
        return new self($loanNumber);
    }
}