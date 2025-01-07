<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case cash = 'Cash on delivery';
    case esewa = 'Esewa';
    case khalti = 'Khalti';
}
