<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    use HasFactory  ;
    public $guarded = [];

    // public function admins() {
 
    //     return $this->belongsToMany(User::class,'admins_roles','role_id','admin_id');
    // }

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'roles_permissions', 'role_id', 'permission_id');
    // }
}
