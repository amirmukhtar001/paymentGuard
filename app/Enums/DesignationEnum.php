<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum DesignationEnum: string
{
    use ArrayableEnum;

    case SECRETARY = 'Secretary';
    case DIRECTOR = 'Director';
    case MANAGER = 'Manager';
    case EMPLOYEE = 'Employee';
}
