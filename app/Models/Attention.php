<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\type;

class Attention extends Model
{
    use HasFactory;
    /*
    person_type
    001: Estudiante
    002: Profesor
    003: Administrativo / Obrero / Obras
    004: Externo
    */


    protected $fillable = [
        'person_type',
        'person_id',
        'report_number',
        'description',
        'derivations',
        'type_attention_id',
        'user_id',
        'type_person',
    ];

    protected $casts = [
        'created_at' => 'datetime:d/m/Y H:i:s',
    ];

    protected $appends = [
        'person_type_name',
    ];

    public function getPersonTypeNameAttribute()
    {
        if (isset($this->attributes['person_type'])) {
            switch ($this->attributes['person_type']) {
                case '001':
                    return 'Estudiante';
                    break;
                case '002':
                    return 'Docente';
                    break;
                case '003':
                    return 'Administrativo / Obrero / Obras';
                    break;
                case '004':
                    return 'Externo';
                    break;
                default:
                    return 'No definido';
                    break;
            }
        }
        return false;
    }



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 0);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('attentions.created_at', 'desc');
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('attentions.created_at', now());
    }

    //al tener un relacion polimorfica con person_id y person_type
    //yna consulta para obtener el nombre de la persona y su carrera u oficina segun el tipo de persona



}
