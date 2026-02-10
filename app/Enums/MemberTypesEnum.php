<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum MemberTypesEnum: string
{
    use ArrayableEnum;
    case CABINETMEMBER = 'CABINETMEMBER';
    case OTHER = 'OTHER';
    case NORECORD = 'NORECORD';
}
