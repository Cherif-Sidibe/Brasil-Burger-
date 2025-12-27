<?php

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TextArrayType extends Type
{
    public const NAME = 'text_array';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TEXT[]';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): array
    {
        if ($value === null || $value === '') {
            return [];
        }

        // PostgreSQL renvoie les arrays au format {val1,val2,val3}
        if (is_string($value)) {
            $value = trim($value, '{}');
            if ($value === '') {
                return [];
            }
            return explode(',', $value);
        }

        return is_array($value) ? $value : [];
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (empty($value)) {
            return null;
        }

        // Convertir le tableau PHP au format PostgreSQL {val1,val2,val3}
        if (is_array($value)) {
            return '{' . implode(',', $value) . '}';
        }

        return $value;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
