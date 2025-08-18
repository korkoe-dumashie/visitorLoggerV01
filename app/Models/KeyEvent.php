<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeyEvent extends Model
{

    protected $table = 'key_events';
    protected $guarded = [];
    use HasFactory;


    public function employee(): BelongsTo {
        return $this->belongsTo(Employee::class, 'picked_by');
    }

    public function key(): BelongsTo {
        return $this->belongsTo(Key::class, 'key_number');
    }
}
