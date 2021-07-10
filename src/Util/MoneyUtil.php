<?php
declare(strict_types=1);

namespace App\Util;

final class MoneyUtil
{
    public static function amountFromString(string $string): int
    {
        if (!\is_numeric($string)) {
            throw new \InvalidArgumentException(\sprintf('Price amounts should be numeric, received "%s"', $string));
        }
        return self::amountFromFloat((float)$string);
    }

    public static function amountFromFloat(float $float): int
    {
        return (int) \round($float * 1_0000);
    }
}
