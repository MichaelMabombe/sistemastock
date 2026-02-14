<?php

namespace App\Enums;

enum AlertType: string
{
    case BELOW_MINIMUM = 'below_minimum';
    case SLOW_MOVING = 'slow_moving';
    case NEAR_EXPIRY = 'near_expiry';
    case TOP_SELLER = 'top_seller';
    case LOW_SELLER = 'low_seller';
}

