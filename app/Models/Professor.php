<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'paternal_surname',
        'maternal_surname',
        'document_type',
        'document_number',
        'birthdate',
        'phone_number',
        'career_code',
        'condition_id',
        'position_id',
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

    protected $appends = [
        'career_name',
        // 'condition_name',
        // 'position_name'
    ];

    public function getCareerNameAttribute()
    {
        return $this->attributes['career_code'] ? Career::where('code', $this->attributes['career_code'])->first()->name : null;
    }

    // public function getConditionNameAttribute()
    // {
    //     return $this->condition_id ? Condition::find($this->condition_id)->name : null;
    // }

    // public function getPositionNameAttribute()
    // {
    //     return $this->position_id ? Position::find($this->position_id)->name : null;
    // }

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
