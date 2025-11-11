<?php

namespace App\Enum;

enum Status: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case FLAGGED = 'flagged';
    case RESOLVED = 'resolved';
    case DISMISSED = 'dismissed';

        public static function topicStatuses(): array
    {
        return [
            self::PENDING,
            self::APPROVED,
            self::REJECTED,
            self::FLAGGED,
        ];
    }
        public static function commentStatuses(): array
    {
        return [
            self::PENDING,
            self::APPROVED,
            self::REJECTED,
            self::FLAGGED,
        ];
    }
    public static function reportStatues(): array
    {
        return [
            self::PENDING,
            self::RESOLVED,
            self::DISMISSED,
        ];
    }
}
