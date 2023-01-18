<?php

namespace App\Types;

use App\Models\ValueObjects\LoanNumber;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class LoanNumberType extends Type
{
    public const NAME = 'loan_number';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        // TODO: Implement getSQLDeclaration() method.
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return LoanNumber::make($value);
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
