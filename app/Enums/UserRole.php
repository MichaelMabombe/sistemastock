<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case WAREHOUSE_MANAGER = 'warehouse_manager';
    case OPERATOR = 'operator';
}

