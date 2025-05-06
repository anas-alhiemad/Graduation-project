<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'file_path'
    ];

    // Relationship with Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
