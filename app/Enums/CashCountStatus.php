<?php

namespace App\Enums;

enum CashCountStatus: string
{
    case Draft = 'draft';
    case Submitted = 'submitted';
    case Locked = 'locked';
}
