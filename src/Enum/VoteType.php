<?php

namespace App\Enum;

enum VoteType: string
{
    case UPVOTE = 'upvote';
    case DOWNVOTE = 'downvote';
}
