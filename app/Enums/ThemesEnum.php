<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum ThemesEnum: string
{
    use ArrayableEnum;
    case ACTIVE = 'Default';
    case INACTIVE = 'Skin';

}
