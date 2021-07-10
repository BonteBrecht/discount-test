<?php
declare(strict_types=1);

namespace App\Util;

final class MoneyUtil
{
    public static function amountFromString(string $string): int
    {
        return self::amountFromFloat((float)$string);
    }

    public static function amountFromFloat(float $float): int
    {
        return (int) ($float * 1000);
    }
}
