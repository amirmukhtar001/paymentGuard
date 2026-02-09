<?php

namespace App\Enums;

enum BusinessType: string
{
    case Restaurant = 'restaurant';
    case Clinic = 'clinic';
    case Retail = 'retail';
    case Salon = 'salon';
}
