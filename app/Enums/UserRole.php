<?php

namespace App\Enums;

class UserRole
{
    public const MANAGER = 1;

    public const USER = 2;

    public static function all()
    {
        $user_role = [
            [
                "value" => self::MANAGER,
                "name" => 'manager',
            ],
            [
                "value" => self::USER,
                "name" => 'user',
            ]
        ];
        return $user_role;
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::MANAGER:
                return 'manager';
            case self::USER:
                return 'user';
            default:
                throw new \InvalidArgumentException("Invalid user role value: $value");
        }
    }

    public static function getValue($value)
    {
        $user_role = self::all();

        return $user_role[$value] ?? null;
    }
}
