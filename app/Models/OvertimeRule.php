<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'scope', 'area_id', 'office_id', 'min_minutes_threshold',
        'rounding_minutes', 'daily_cap_minutes', 'weekly_cap_minutes', 'requires_approval',
    ];
}


