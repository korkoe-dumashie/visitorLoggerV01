<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{


    protected $guarded = [];
    use HasFactory;

    public function setDevicesAttribute($value)
    {
        $this->attributes['devices'] = json_encode(is_array($value) ? $value : [$value]);
    }

    public function employee(){
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}
