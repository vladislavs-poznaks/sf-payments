<?php

namespace App\Types;

use Carbon\Carbon;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class CarbonType extends Type
{
    public const NAME = 'carbon';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform)
    {
        // TODO: Implement getSQLDeclaration() method.
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return Carbon::parse($value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value->toDatetimeString();
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
