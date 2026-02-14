<?php

namespace App\Enums;

enum SupplierTransactionType: string
{
    case DEBT = 'debt';
    case PAYMENT = 'payment';
}

