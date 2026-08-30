<?php

namespace App\Support;

class SoftDeleteTombstone
{
    public static function encodeId(int $id): string
    {
        return strtolower(base_convert((string) $id, 10, 36));
    }

    public static function value(int $id, ?string $original = null): string
    {
        $tombstone = self::encodeId($id).'|deleted';

        if (!empty($original)) {
            $tombstone .= '|'.$original;
        }

        return $tombstone;
    }
}
