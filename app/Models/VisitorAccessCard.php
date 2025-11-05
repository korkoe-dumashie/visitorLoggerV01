<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitorAccessCard extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = 'visitor_access_cards';


    public function access():BelongsToMany{
        return $this->belongsToMany(Visitor::class, 'access_card', 'visitor_id', 'card_id');
    }
}
