<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeAttention extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'type',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'description'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
