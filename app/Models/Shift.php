<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'start_time', 'end_time', 'is_overnight',
        'break_minutes', 'grace_minutes', 'expected_hours', 'active',
    ];
}


