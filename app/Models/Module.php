<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $guarded = [];
    protected $table = 'modules';
    use HasFactory;

    public function role(){
        return $this->belongsToMany(Roles::class,'permissions', 'module_id', 'role_id')->withPivot( 'can_view','can_create', 'can_modify', 'can_delete');
    }

}
