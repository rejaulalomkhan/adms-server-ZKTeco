<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShiftRotation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'cycle_length_weeks', 'effective_date', 'expiry_date',
    ];
}


