<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait HasUuid
{
    protected static function bootHasUuid()
    {
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Ensure uuid is always included in model's mass-assignable attributes
     */
    public function initializeHasUuid()
    {
        // Only append to fillable when the model explicitly defines it.
        if (!empty($this->fillable) && !in_array('uuid', $this->fillable, true)) {
            $this->fillable[] = 'uuid';
        }
    }
}
