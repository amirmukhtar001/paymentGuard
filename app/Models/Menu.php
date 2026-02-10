<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['parent_id', 'title', 'description', 'icon', 'order', 'is_collapsible'];

    protected $connection = "mysql";

    protected $table = "menus";

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive', 'myPermissions');
    }

    public function myPermissions()
    {
        return $this->hasMany(MyPermission::class, 'menu_id', 'id');
    }

    public function routes()
    {
        return $this->hasMany(PermissionRoute::class, 'menu_id', 'id');
    }
}
