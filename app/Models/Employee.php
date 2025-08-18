<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Employee extends Model
{

    protected $table = 'employees';
    protected $guarded = [];
    use HasFactory;


    public function department(){
        return $this->belongsTo(Department::class);
    }


    public function visitors()
    {
        return $this->hasMany(Visitor::class, 'employee_Id', 'id');
    }

    
    public function ownDevice(){
        return $this->hasMany(Device::class);
    }

    public function pickKey():BelongsToMany{
        return $this->belongsToMany(Key::class,
        'key_events','picked_by',
        'key_number')
        ->withPivot('picked_at','returned_at','status')
        ->withTimestamps();
    }
    // public function returnKey()

    //WORKING ON RETURN KEY RELATIONSHIP
    


}
