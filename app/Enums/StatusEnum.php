<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum StatusEnum: string
{
    use ArrayableEnum;
    case ACTIVE = 'Active';
    case INACTIVE = 'InActive';

}
