<?php

namespace App\Enums;

enum MovementType: string
{
    case ENTRY = 'entry';
    case SALE = 'sale';
    case INTERNAL_USE = 'internal_use';
    case LOSS = 'loss';
    case ADJUSTMENT = 'adjustment';
    case TRANSFER_IN = 'transfer_in';
    case TRANSFER_OUT = 'transfer_out';
    case INVENTORY_ADJUSTMENT = 'inventory_adjustment';
}

