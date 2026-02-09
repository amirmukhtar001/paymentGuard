<?php

namespace App\Enums;

enum DifferenceType: string
{
    case Balanced = 'balanced';
    case Over = 'over';
    case Short = 'short';
}
