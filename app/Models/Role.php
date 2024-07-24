<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as ModelsRole;

// que exti
class Role extends ModelsRole 
{
    use HasFactory;

    protected $fillable = ['name', 'guard_name'];

    //scope by created_at
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }



    //add permissions in get
    

}
