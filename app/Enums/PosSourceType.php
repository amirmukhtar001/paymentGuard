<?php

namespace App\Enums;

enum PosSourceType: string
{
    case Manual = 'manual';
    case FileImport = 'file_import';
    case Api = 'api';
}
