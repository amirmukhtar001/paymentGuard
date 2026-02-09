<?php

namespace App\Enums;

enum ShiftStatus: string
{
    case Open = 'open';
    case Closed = 'closed';
    case Reconciled = 'reconciled';
    case Locked = 'locked';
}
