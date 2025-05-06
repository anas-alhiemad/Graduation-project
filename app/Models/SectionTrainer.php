<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SectionTrainer extends Model
{
    use HasFactory;

    protected $fillable = ['course_section_id','trainer_id'];
}
