<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $guarded = [];
    protected $table = 'modules';
    use HasFactory;

    public function user_roles(){
        return $this->belongsToMany(Roles::class,'permissions')->withPivot( 'view','create', 'modify', 'delete');
    }
    
}
