<?php


namespace App\Enums;

enum ConnectionStatusEnum: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';

    public static function values()
    {
        return array_column(self::cases(), 'value');
    }
}