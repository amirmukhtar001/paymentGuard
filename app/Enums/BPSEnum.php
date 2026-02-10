<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum BPSEnum: string
{
    use ArrayableEnum;

    case BPS_17 = '17';
    case BPS_18 = '18';
    case BPS_19 = '19';
    case BPS_20 = '20';
}
