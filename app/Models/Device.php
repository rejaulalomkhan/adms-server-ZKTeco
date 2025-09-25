<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'no_sn', 'lokasi', 'online', 'office_id',
    ];

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
