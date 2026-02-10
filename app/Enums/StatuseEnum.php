<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum StatuseEnum: string
{
    use ArrayableEnum;
    case ACTIVE = 'Active';
    case DRAFT = 'Draft';
    case CLOSED = 'Closed';
    
}
