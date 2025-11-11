<?php

namespace App\Enum;

enum Reason: string
{
    case SPAM = 'spam';
    case INAPPROPRIATE = 'inappropriate';
    case HARASSMENT = 'harassment';
    case OTHER = 'other';
}
