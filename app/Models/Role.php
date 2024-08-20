<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelsRole;

// que exti
class Role extends ModelsRole
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'is_super'
    ];

    protected $casts = [
        'is_super' => 'boolean',
    ];
    protected $hidden = ['pivot'];

    //scope by created_at
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    //scope, listar todos los reoles pero solo el super cuando es super
    public function scopeNotSuper($query)
    {
        return $query->where('is_super', false);
    }
}
