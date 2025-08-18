<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Key extends Model
{

    
    protected $table = 'keys';
    protected $guarded = [];
    use HasFactory;

    // public function pickedBy():BelongsToMany{
    //     return $this->belongsToMany(Employee::class,'employees','key_number','picked_by');
    // }

    // public function returnedBy():BelongsToMany{
    //     return $this->belongsToMany(Employee::class,'employees','key_number','returned_by');
    // }

    public function pickKey(){
        return $this->belongsToMany(Employee::class,'key_events','key_number','picked_by')
        ->withPivot('picked_at','returned_at','status')
        ->withTimestamps();
    }

}
