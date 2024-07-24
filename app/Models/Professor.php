<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'paternal_surname',
        'maternal_surname',
        'document_type',
        'document_number',
        'birthdate',
        'phone_number',
        'career_code',
        'professor_code',
        'email',
        'gender',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'birthdate' => 'date:Y-m-d'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
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

    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }
}
