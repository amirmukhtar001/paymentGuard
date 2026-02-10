<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum DisplayOnHomeEnum: string
{
    use ArrayableEnum;

    case YES = 'yes';
    case NO = 'no';
}
