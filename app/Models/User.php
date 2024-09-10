<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasRoles, HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'paternal_surname',
        'maternal_surname',
        'document_type',
        'document_number',
        'email',
        'password',
        'office_id',
        'is_editable',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $appends = [
        'role_id',
        'role_name',
        'office_name',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'is_editable' => 'boolean',
        ];
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->name} {$this->paternal_surname} {$this->maternal_surname}";
    }

    public function getDocumentAttribute(): string
    {
        return "{$this->document_type} {$this->document_number}";
    }

    public function getRoleNameAttribute(): string
    {
        return $this->roles ? $this->roles->first()->name : '';
    }

    public function getOfficeNameAttribute(): string
    {
        return $this->office ? $this->office->name : '';
    }

    public function getRoleIdAttribute(): int
    {
        return $this->roles ? $this->roles->first()->id :null;
    }
}
