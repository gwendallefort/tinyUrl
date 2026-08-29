<?php

namespace App\Support;

class SoftDeleteTombstone
{
    public static function encodeId(int $id): string
    {
        return strtolower(base_convert((string) $id, 10, 36));
    }

    public static function value(int $id, string $original): string
    {
        return self::encodeId($id).'|deleted|'.$original;
    }
}
