<?php

namespace App\Config;

enum NotificationChannel: string
{
    case Sms = 'sms';
    case Email = 'email';

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}