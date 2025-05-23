<?php

namespace App\Models;


use App\Models\CourseSection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Section_File extends Model
{
    use HasFactory;
    protected $fillable = ['file_name','file_path','course_section_id'];

    public function courseSection()
    {
        return $this->belongsTo(CourseSection::class,'course_section_id');
    }

}
