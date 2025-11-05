<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{Model,SoftDeletes};

class Department extends Model
{

    use HasFactory,SoftDeletes;
    protected $table = 'departments';

    protected $guarded = [];


    public function employees(){
        return $this->hasMany(Employee::class);
    }
}
