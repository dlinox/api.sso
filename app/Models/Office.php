<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'ubication',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'description',
        'ubication'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', false);
    }

    // order by created_at desc
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // order by created_at asc
    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }
}
