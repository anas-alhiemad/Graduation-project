<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'student_id',
        'is_present'
    ];

    protected $casts = [
        'is_present' => 'boolean'
    ];

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
} 