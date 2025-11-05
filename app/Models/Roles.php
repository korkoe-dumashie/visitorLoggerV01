<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\{SoftDeletes,Model};
use Illuminate\Database\Eloquent\Relations\{BelongsToMany,HasMany};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Roles extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'roles';

    protected $guarded = [];


    public function users(): HasMany{
        return $this->hasMany(User::class);
    }


    public function modules():  BelongsToMany{
        return $this->belongsToMany(Module::class,  'permissions')->withPivot('view','create','modify','delete');
    }


    public static function hasPermission($role_id, $moduleName, $action): bool{
        $permissionColumn = "can_$action";

        // return true;
        $check = DB::table('permissions')
        ->join('modules', 'modules.id', '=', 'permissions.module_id')
        ->select($permissionColumn)
        ->where('role_id','=',$role_id)
        //->where('module_id','=',$moduleName)
        ->where('name','=',$moduleName)
        ->get();

        // Log::debug("Module: " . $moduleName);
        // Log::debug($check);
        // Log::debug($permissionColumn);
      return ($check->value($permissionColumn) == 1) ?true: false;



    }

}
