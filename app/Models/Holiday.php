<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'date', 'is_recurring', 'area_id', 'office_id',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}


