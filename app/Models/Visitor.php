<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Visitor extends Model
{

    protected $table = 'visits';

    protected $guarded = [];
    use HasFactory;



    public function visitee()
    {
        return $this->belongsTo(Employee::class, 'employee_Id', 'id');
    }

public function accessCard():BelongsToMany{
    return $this->belongsToMany(VisitorAccessCard::class, 'access_cards', 'visitor_id', 'card_number');
}

    protected $casts = [
        'devices' => 'array',
        'companions' => 'array',
        'departed_at' => 'datetime',
    ];


}


