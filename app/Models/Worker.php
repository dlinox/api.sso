<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    /*
    type
    001: Administrativo
    002: Cas
    003: Obrero
    004: Profecional de Obra
    005: Docente
    */

    protected $fillable = [
        'name',
        'paternal_surname',
        'maternal_surname',
        'document_type',
        'document_number',
        'condition_id',
        'position_id',
        'office_id',
        'code',
        'type',
        'birthdate',
        'phone_number',
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
        'office_name',
        'type_name',
        // 'condition_name',
        // 'position_name'
    ];


    public function getOfficeNameAttribute()
    {
        return $this->attributes['office_id'] ? Office::where('id', $this->attributes['office_id'])->first()->name : null;
    }

    public function getTypeNameAttribute()
    {
        switch ($this->attributes['type']) {
            case '001':
                return 'Administrativo';
                break;
            case '002':
                return 'Cas';
                break;
            case '003':
                return 'Obrero';
                break;
            case '004':
                return 'Profecional de Obra';
                break;
            case '005':
                return 'Docente';
                break;
            default:
                return 'No definido';
                break;
        }
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
