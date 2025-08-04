<?php

namespace App\Models;

use App\Models\Secretary;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['title','description','status','secretary_id'];

    public function secretaries() 
    {
       return $this->belongsTo(Secretary::class,'secretary_id');
    }
}
