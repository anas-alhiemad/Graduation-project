<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'exam_date',
        'course_section_id'
    ];

    protected $casts = [
        'exam_date' => 'datetime'
    ];

    public function courseSection(): BelongsTo
    {
        return $this->belongsTo(CourseSection::class);
    }
    public function grades()
{
    return $this->hasMany(Grade::class);
}

}
