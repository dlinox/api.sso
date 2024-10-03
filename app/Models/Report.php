<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'type',
        'number',
        'year',
        'payload',
        'user_id'
    ];

    //next number by type and year 

    public static function nextNumber($type, $year)
    {
        $last = Report::where('type', $type)
            ->where('year', $year)
            ->orderBy('number', 'desc')
            ->first();

        if ($last) {
            return str_pad($last->number + 1, 4, '0', STR_PAD_LEFT);
        } else {
            return '0001';
        }
    }
}
