<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftRotationWeek extends Model
{
    use HasFactory;

    protected $fillable = [
        'rotation_id', 'week_index', 'shift_id',
    ];
}


