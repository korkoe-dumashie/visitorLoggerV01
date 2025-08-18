<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSessions extends Model
{

    protected  $table = 'user_sessions';
    protected $guarded = [];
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);


    }

    
}
