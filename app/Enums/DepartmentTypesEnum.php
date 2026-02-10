<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum DepartmentTypesEnum: string
{
    use ArrayableEnum;
    case ATTACHED_DEPARTMENT = 'Attached Department';
    case ADMINISTRATIVE_DEPARTMENT = 'Administrative Department';
    case SPECIAL_INSTITUTIONS = 'Special Institutions';
    case AUTONOMOUS_BODIES = 'Autonomous Bodies';
    case AUTHORITY = 'Authority';
    case OTHER = 'Other';

}