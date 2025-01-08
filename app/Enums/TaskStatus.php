<?php

namespace App\Enums;

class TaskStatus
{
    public const PENDING = 1;

    public const COMPLETED = 2;

    public const CANCELED = 3;

    public $task_status;

    public static function all()
    {
        $task_status = [
            [
                "value" => self::PENDING,
                "name" => 'pending',
            ],
            [
                "value" => self::COMPLETED,
                "name" => 'completed',
            ],
            [
                "value" => self::CANCELED,
                "name" => 'canceled',
            ]
        ];
        return $task_status;
    }

    public static function getStringValue($value)
    {
        switch ($value) {
            case self::PENDING:
                return 'pending';
            case self::COMPLETED:
                return 'completed';
            case self::CANCELED:
                return 'canceled';
            default:
                throw new \InvalidArgumentException("Invalid task status value: $value");
        }
    }

    public static function getValue($value)
    {
        $task_status = self::all();

        return $task_status[$value] ?? null;
    }
}
