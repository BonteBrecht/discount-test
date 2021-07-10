<?php
declare(strict_types=1);

namespace App\Util;

final class Json
{
    public static function encode(mixed $data): string
    {
        $json = \json_encode($data, JSON_THROW_ON_ERROR);
        \assert($json !== false);

        return $json;
    }
}
