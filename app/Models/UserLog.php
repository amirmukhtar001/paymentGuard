<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class UserLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'action', 'ip_address', 'user_agent'];

    protected $connection = 'mysql';

    protected $table = 'user_logs';

    protected $hidden = ['updated_at', 'deleted_at'];

    /**
     * Get the user that owns the log.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
