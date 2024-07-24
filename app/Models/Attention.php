<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\type;

class Attention extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'report_number',
        'description',
        'derivations',
        'student_id',
        'type_attention_id',
        'user_id',
        'type_person',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function typeAttention()
    {
        return $this->belongsTo(TypeAttention::class);
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
}
