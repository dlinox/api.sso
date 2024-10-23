<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
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
        'student_code',
        'email',
        'gender',
        'status',
        'mother_name',
        'father_name'
    ];

    protected $casts = [
        'status' => 'boolean',
        'birthdate' => 'date:Y-m-d'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $appends = [
        'full_name',
        'career_name'
    ];

    public function getCareerNameAttribute()
    {
        return Career::where('code', $this->attributes['career_code'])->first()->name ?? null;
    }

    public function getFullNameAttribute()
    {
        return $this->attributes['name'] . ' ' . $this->attributes['paternal_surname'] . ' ' . $this->attributes['maternal_surname'];
    }

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
