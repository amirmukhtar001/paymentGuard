<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use jeremykenedy\LaravelRoles\Models\Permission;

class MyPermission extends Permission
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'description', 'model', 'menu_id', 'show_in_menu'];

    protected $connection = "mysql";

    protected $table = "permissions";

    protected static function newFactory()
    {
        return \Database\Factories\MyPermissionFactory::new();
    }



    // permission and role many to many
    public function roles(){
        return $this->belongsToMany(MyRole::class, 'permission_role', 'permission_id', 'role_id')->withTimestamps();
    }

    // permission and user many to many (permission can be directly assigned to user too
    public function users(){
        return $this->belongsToMany(User::class, 'permission_user', 'permission_id', 'user_id')->withTimestamps();
    }

    public function menu(){
        return $this->belongsTo(Menu::class, 'menu_id', 'id');
    }

    public function routes(){
        return $this->hasMany(PermissionRoute::class, 'permission_id', 'id');
    }
}
