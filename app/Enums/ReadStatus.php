<?php

namespace App\Enums;

enum ReadStatus: string
{
    case Read = 'Read';
    case Unread = 'Unread';
}