<?php

namespace App\Enums;

enum SaleItemStatus: string
{
    case pending = 'pending';
    case packaging = 'packaging';
    case moved = 'moved';
    case delivered = 'delivered';
    case cancel = 'cancel';
    
}
