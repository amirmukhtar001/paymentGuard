<?php

namespace App\Enums;

use App\Traits\ArrayableEnum;

enum CaderEnum: string
{
    use ArrayableEnum;
   case PAS = 'PAS';
    case PSP = 'PSP';
    case FSP = 'FSP';
    case IRS = 'IRS';
    case PCS = 'PCS';
    case PAAS = 'PAAS';
    case CTG = 'CTG';
    case IG = 'IG';
    case MLCG = 'MLCG';
    case OMG = 'OMG';
    case PG = 'PG';
    case PMS = 'PMS';
    case GENERAL = 'General';
    case TECHNICAL = 'Technical';
    case OTHER = 'Other';
}
