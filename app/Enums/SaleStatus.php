<?php

namespace App\Enums;

enum SaleStatus: string
{
    case active = 'active';
    case cancelled = 'cancelled';
}

enum SaleStateEnum: string
{
    case pending = 'pending';
    case processing = 'processing';
    case ready = "ready";
    case delivered = 'delivered';
}
