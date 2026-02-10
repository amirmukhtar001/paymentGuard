<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'company_type_id',
        'parent_id',
        'title',
        'description',
        'prefix',
        'user_id',
        'short_code',
        'domain',
        'domain_prefix',
        'contact_email',
        'contact_phone',
        'address_line1',
        'address_line2',
        'postal_code',
        'status',
        'launched_at',
        'deactivated_at',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    public function type()
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Company::class, 'parent_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'company_id', 'id');
    }

    protected static function newFactory()
    {
        return \Database\Factories\CompanyFactory::new();
    }
}
