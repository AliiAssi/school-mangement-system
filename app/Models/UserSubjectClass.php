<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSubjectClass extends Model
{
    protected $table = 'teacher_subject_class';
    
    // Define the attributes that are mass assignable
    protected $fillable = [
        'user_id',
        'subject_id',
        'class_id',
        'weekly_sessions',
    ];
}
