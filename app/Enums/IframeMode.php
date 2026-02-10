<?php

namespace App\Enums;

enum IframeMode: int
{
    case DISABLED = 0;
    case ENABLED = 1;

    public function label(): string
    {
        return match($this) {
            self::DISABLED => 'Disabled',
            self::ENABLED => 'Enabled',
        };
    }

    public function isEnabled(): bool
    {
        return $this === self::ENABLED;
    }
}
