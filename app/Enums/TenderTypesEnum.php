<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum TenderTypesEnum: string
{
    use ArrayableEnum;
    case WORK = 'Work';
    case GOODS = 'Goods';
    case SERVICES = 'Services';
}
