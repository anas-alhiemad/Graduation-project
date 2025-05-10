<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'file',
        'secretary_id'
    ];

    public function secretary()
    {
        return $this->belongsTo(Secretary::class);
    }
} 