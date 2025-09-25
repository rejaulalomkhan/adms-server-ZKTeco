<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OvertimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'date', 'minutes', 'type', 'approved_by', 'approved_at',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}


