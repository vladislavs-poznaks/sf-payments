<?php

namespace App\Types;

use App\Models\ValueObjects\Amount;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class AmountType extends Type
{
    public const NAME = 'amount';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        // TODO: Implement getSQLDeclaration() method.
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Amount::make($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->getAmount();
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
